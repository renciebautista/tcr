<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;
use App\Audit;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class CustomerReportController extends Controller
{
    public function index(){
    	$customers = PostedAudit::getCustomers()->lists('customer','customer_code');
        $regions = PostedAudit::getRegions()->lists('region','region_code');
        $templates = PostedAudit::getTemplates()->lists('template','channel_code');
        $audits = PostedAudit::getAudits()->lists('description','audit_id');
        $customer_summaries = PostedAudit::getCustomerSummary();
    	return view('customerreport.index', compact('customers','regions','templates', 'audits', 'customer_summaries'));
    }

    public function create(Request $request){
        $customer_summaries = PostedAudit::getCustomerSummary($request);
        if($request->submit == 'process'){
            $request->flash();
            $customers = PostedAudit::getCustomers()->lists('customer','customer_code');
            $regions = PostedAudit::getRegions()->lists('region','region_code');
            $templates = PostedAudit::getTemplates()->lists('template','channel_code');
            $audits = PostedAudit::getAudits()->lists('description','audit_id');
            return view('customerreport.index', compact('customers','regions','templates', 'audits', 'customer_summaries'));
        }
        else{
            set_time_limit(0);
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('Customer Summary Report.csv');
            $writer->addRow(['customer', 'region', 'audit_template', 'audit_month', 'stores_mapped', 'stores_visited', 'perfect_store', 'ave_osa', 'ave_npi', 'ave_planogram']); 

            foreach($customer_summaries as $row)
            {
                $row_data[0] = $row->customer;
                $row_data[1] = $row->region;
                $row_data[2] = $row->audit_tempalte;
                $row_data[3] = $row->audit_group;
                $row_data[4] = $row->mapped_stores;
                $row_data[5] = $row->visited_stores;
                $row_data[6] = $row->perfect_stores;
                $row_data[7] = number_format($row->osa_ave,2);
                $row_data[8] = number_format($row->npi_ave,2);
                $row_data[9] = number_format($row->planogram_ave,2);
                $writer->addRow($row_data); // add multiple rows at a time
            }   
            $writer->close();
        }

        
    }

    public function show($customer_code,$region_code,$channel_code,$audit_id){
        $audit = Audit::findOrFail($audit_id);
        $data['customer_code'] = $customer_code;
        $data['region_code'] = $region_code;
        $data['channel_code'] = $channel_code;
        $data['audit_id'] = $audit_id;
        $posted_audits = PostedAudit::customerSearch($data);
        $customer = PostedAudit::getCustomer($customer_code,$audit_id);
        $region = PostedAudit::getRegion($region_code,$audit_id);
        $template = PostedAudit::getTemplate($channel_code,$audit_id);
        return view('customerreport.show',compact('posted_audits', 'customer', 'region', 'template', 'audit'));
    }
}
