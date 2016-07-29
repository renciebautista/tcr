<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;
use App\Audit;
use Auth;
use App\Role;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Input;
use Session;
use Response;
class CustomerRegionalReportController extends Controller
{
    public function index(){
         set_time_limit(0);
        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);

        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

            $use = PostedAudit::getUsers($auth_user);  
            $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');
            $regions = PostedAudit::getRegions($use,$role)->lists('region','region_code'); 
            $customer_summaries = PostedAudit::getCustomerSummaryDefault($use);
                     
        }

        if($role->role_id === 3){
            
            $temp = PostedAudit::getTemplatesMT($auth_user);              
            $use = PostedAudit::getTemplatesMT($auth_user);            
            $customers = PostedAudit::getCustomersMT($temp)->lists('customer','customer_code');
            $regions = PostedAudit::getRegions($temp,$role)->lists('region','region_code');            
            $customer_summaries = PostedAudit::getCustomerSummaryDefault($use);

        }
        
        

        $audits = PostedAudit::getAudits()->lists('description','audit_id');        

        $posted_audits = $customer_summaries;
        $p_store_average = PostedAudit::getPerfectStoreAverageInCustomerReport($posted_audits);
        $osa_average = PostedAudit::getOsaAverage($posted_audits);
        $npi_average = PostedAudit::getNpiAverage($posted_audits);
        $planogram_average = PostedAudit::getPlanogramAverage($posted_audits);
       
    	return view('customerregionalreport.index', compact('customers','regions', 'audits', 'customer_summaries','p_store_average','osa_average','npi_average','planogram_average'));
    }    

     public function create(Request $request){
         set_time_limit(0);
        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);

        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){
            
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
                $cus = $request->get('customers');
                $regions = PostedAudit::getRegionsfilter($use,$cus,$role)->lists('region','region_code');

            }

            if($role->role_id === 3){

                $temp = PostedAudit::getTemplatesMT($auth_user);                
                $customers = PostedAudit::getCustomersMT($temp)->lists('customer','customer_code');
                $cus = $request->get('customers');
                $regions = PostedAudit::getRegionsfilterR($temp,$cus,$role)->lists('region','region_code');
                
            }
            
            $audits = PostedAudit::getAudits()->lists('description','audit_id');
            return view('customerregionalreport.index', compact('customers','regions', 'audits', 'customer_summaries','p_store_average','npi_average','osa_average','planogram_average'));
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
    public function regionsfilter(){

        $auth_user = Auth::id();
        $cus = Input::get('customers');
        $id = $auth_user;
        $role = Role::myroleid($id);

        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

            $use = PostedAudit::getUsers($auth_user);
        }
        if($role->role_id === 3){

            $use = PostedAudit::getTemplatesMT($auth_user);            
        }
        
        $regions = PostedAudit::getRegionsfilter($use,$cus,$role)->lists('region','region_code');
        
        return Response::json($regions);        
     
    }

    public function monthfilterregional(){

        $auth_user = Auth::id();
        $customer = Input::get('customers');
        $region = Input::get('regions');
        $id = $auth_user;
        $role = Role::myroleid($id);

        $audits = PostedAudit::getauditfiltersAFRegion($customer,$region)->lists('description','audit_id');

        return Response::json($audits);
    }    
}
