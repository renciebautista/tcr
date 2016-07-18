<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;
use App\Audit;
use Auth;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Input;
use Session;
use Response;
class CustomerRegionalReportController extends Controller
{
    public function index(){
        $auth_user = Auth::id();
        $use = PostedAudit::getUsers($auth_user);  
    	$customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');
        $regions = PostedAudit::getRegions($use)->lists('region','region_code');
        $templates = PostedAudit::getTemplates($auth_user)->lists('template','channel_code');
        $audits = PostedAudit::getAudits()->lists('description','audit_id');
        $customer_summaries = PostedAudit::getCustomerSummaryDefault($use);
        $posted_audits = $customer_summaries;
        $p_store_average = PostedAudit::getPerfectStoreAverageInCustomerReport($posted_audits);
        $osa_average = PostedAudit::getOsaAverage($posted_audits);
        $npi_average = PostedAudit::getNpiAverage($posted_audits);
        $planogram_average = PostedAudit::getPlanogramAverage($posted_audits);
        // $stores_visited_ave = PostedAudit::getTotalStoresVisitedAve($customer_summaries);
        // $perfect_stores = PostedAudit::getTotalPerfectStores($customer_summaries);
        // $perfect_stores_percentage= PostedAudit::getTotalPerfectStoresPercentage($customer_summaries);
        // $total_perfect_store_ave = PostedAudit::getTotalPerfectStoreAverage($customer_summaries);    
    	return view('customerregionalreport.index', compact('customers','regions','templates', 'audits', 'customer_summaries','p_store_average','osa_average','npi_average','planogram_average'));
    }    

     public function create(Request $request){
        $auth_user = Auth::id();
        $use = PostedAudit::getUsers($auth_user);
        $cust = PostedAudit::getCustomers($use);  
        $temp = PostedAudit::getTemplates($auth_user);
        $customer_summaries = PostedAudit::getCustomerSummary($request,$temp,$cust,$use);
        $posted_audits = $customer_summaries;
        $p_store_average = PostedAudit::getPerfectStoreAverageInCustomerReport($posted_audits);
        $osa_average = PostedAudit::getOsaAverage($posted_audits);
        $npi_average = PostedAudit::getNpiAverage($posted_audits);
        $planogram_average = PostedAudit::getPlanogramAverage($posted_audits);
        if($request->submit == 'process'){
            $request->flash();
            $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');
            $regions = PostedAudit::getRegions($use)->lists('region','region_code');
            $templates = PostedAudit::getTemplates($auth_user)->lists('template','channel_code');
            $audits = PostedAudit::getAudits()->lists('description','audit_id');
            return view('customerregionalreport.index', compact('customers','regions','templates', 'audits', 'customer_summaries','p_store_average','npi_average','osa_average','planogram_average'));
        }
        else{
            set_time_limit(0);
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('Customer Regional Summary Report.csv');
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

        $p_store_average = PostedAudit::getPerfectStoreAverageInCustomerReport($posted_audits);
        $osa_average = PostedAudit::getOsaAverage($posted_audits);
        $npi_average = PostedAudit::getNpiAverage($posted_audits);
        $planogram_average = PostedAudit::getPlanogramAverage($posted_audits);


        $customer = PostedAudit::getCustomer($customer_code,$audit_id);
        $region = PostedAudit::getRegion($region_code,$audit_id);
        $template = PostedAudit::getTemplate($channel_code,$audit_id);
        return view('customerregionalreport.show',compact('posted_audits', 'customer', 'region', 'template', 'audit','p_store_average','osa_average','npi_average','planogram_average'));

    }
    public function allregionsfilter(){
            $auth_user = Auth::id();        
            $use = PostedAudit::getUsers($auth_user);  
            $regions = PostedAudit::getRegions($use)->lists('region','region_code');
            return Response::json($regions);                
    }    
    public function regionsfilter(){
        $auth_user = Auth::id();
        $cus = Input::all();        
        if(is_array($cus)){           
            $use = PostedAudit::getUsers($auth_user);         
            $stores = PostedAudit::getRegionsfilter($use,$cus)->lists('region','region_code');
            return Response::json($stores);
        }                    
    }    
}
