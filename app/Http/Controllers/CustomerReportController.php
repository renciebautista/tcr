<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;
use App\Audit;
use App\Role;
use Auth;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class CustomerReportController extends Controller
{
    public function index(){
        
        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);

        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

            $use = PostedAudit::getUsers($auth_user);  
            $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');
            $customer_summaries = PostedAudit::getCustomerSummaryDefault($use);            
        }
        
        if($role->role_id === 3){
            
            $temp = PostedAudit::getTemplatesMT($auth_user);              
            $use = PostedAudit::getTemplatesMT($auth_user);            
            $customers = PostedAudit::getCustomersMT($temp)->lists('customer','customer_code');            
            $customer_summaries = PostedAudit::getCustomerSummaryDefault($use);

        }
        
        $audits = PostedAudit::getAudits()->lists('description','audit_id');        
        $posted_audits = $customer_summaries;
        $p_store_average = PostedAudit::getPerfectStoreAverageInCustomerReport($posted_audits);
        $osa_average = PostedAudit::getOsaAverage($posted_audits);
        $npi_average = PostedAudit::getNpiAverage($posted_audits);
        $planogram_average = PostedAudit::getPlanogramAverage($posted_audits);
        
    	return view('customerreport.index', compact('customers','templates', 'audits', 'customer_summaries','osa_average','npi_average','planogram_average','p_store_average'));
    }

    public function create(Request $request){

        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);

        if($role->role_id === 4){

            $use = PostedAudit::getUsers($auth_user);
            $cust = PostedAudit::getCustomers($use);    
            $customer_summaries = PostedAudit::getCustomerSummary($request,$cust,$use);

        }
        if($role->role_id === 3){
            $temp = PostedAudit::getTemplatesMT($auth_user);
            $use = PostedAudit::getUsersMT($temp);  
            $cust = PostedAudit::getCustomersMT($temp); 
            $customer_summaries = PostedAudit::getCustomerSummary($request,$cust,$use);
        }    
        if($role->role_id === 1 || $role->role_id === 2){
            
            $use = PostedAudit::getUsers($auth_user);
            $cust = PostedAudit::getCustomers($use);              
            $customer_summaries = PostedAudit::getCustomerSummary($request,$cust,$use);
        }    
                
        $posted_audits = $customer_summaries;
        
        $p_store_average = PostedAudit::getPerfectStoreAverageInCustomerReport($posted_audits);
        $osa_average = PostedAudit::getOsaAverage($posted_audits);
        $npi_average = PostedAudit::getNpiAverage($posted_audits);
        $planogram_average = PostedAudit::getPlanogramAverage($posted_audits);
        
        if($request->submit == 'process'){
            $request->flash();

            if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

                $use = PostedAudit::getUsers($auth_user);  
                $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');                
            }

            if($role->role_id === 3){

                $temp = PostedAudit::getTemplatesMT($auth_user);                
                $customers = PostedAudit::getCustomersMT($temp)->lists('customer','customer_code');
            }
                        
            $audits = PostedAudit::getAudits()->lists('description','audit_id');
            return view('customerreport.index', compact('customers','templates', 'audits', 'customer_summaries','osa_average','npi_average','planogram_average','p_store_average'));
        }
        else{
            set_time_limit(0);
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('Customer Summary Report.csv');
            $writer->addRow(['customer', 'audit_template', 'audit_month', 'stores_mapped', 'stores_visited', 'perfect_store', 'ave_osa', 'ave_npi', 'ave_planogram']); 

            foreach($customer_summaries as $row)
            {
                $row_data[0] = $row->customer;                
                $row_data[1] = $row->audit_tempalte;
                $row_data[2] = $row->audit_group;
                $row_data[3] = $row->mapped_stores;
                $row_data[4] = $row->visited_stores;
                $row_data[5] = $row->perfect_stores;
                $row_data[6] = number_format($row->osa_ave,2);
                $row_data[7] = number_format($row->npi_ave,2);
                $row_data[8] = number_format($row->planogram_ave,2);
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
        // $region = PostedAudit::getRegion($region_code,$audit_id);
        $template = PostedAudit::getTemplate($channel_code,$audit_id);
        return view('customerreport.show',compact('posted_audits', 'customer', 'template', 'audit','p_store_average','osa_average','npi_average','planogram_average'));

    }
}
