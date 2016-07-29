<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;
use App\FormCategory;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Auth;
use Input;
use Response;
use App\Role;
class OsaReportController extends Controller
{
    public function index(){
        set_time_limit(0);
        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);  

        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

            $users = PostedAudit::getUsers($auth_user)->lists('name','user_id');
            $use = PostedAudit::getUsers($auth_user);
            $templates = PostedAudit::getTemplates($use)->lists('template','channel_code');                        
            $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');
            $categories = FormCategory::getOSACategories($use)->lists('category','category');
            
        }    

        if($role->role_id === 3){            

            $templates = PostedAudit::getTemplatesMT($auth_user)->lists('template','channel_code'); 
            $temp = PostedAudit::getTemplatesMT($auth_user);
            $users = PostedAudit::getUsersMT($temp)->lists('name','user_id');
            $use = PostedAudit::getUsersMT($temp);                        
            $customers = PostedAudit::getCustomersMT($temp)->lists('customer','customer_code');
            $categories = FormCategory::getOSACategoriesMT($temp)->lists('category','category');            
        }
        
    	$audits = PostedAudit::getAudits()->lists('description','audit_id');
    	$skus = [];

    	return view('osareport.index', compact('audits','templates', 'skus', 'customers', 'categories'));
    }

    public function create(Request $request){
        set_time_limit(0);
        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);

        $use = PostedAudit::getUsers($auth_user); 

        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){            
            
            $skus = PostedAudit::getOsaSku($request,$use);
        }
        if($role->role_id === 3){
            $temp = PostedAudit::getTemplatesMT($auth_user);
            $skus = PostedAudit::getOSASkuMT($request,$temp);            
        }

        
        if($request->submit == 'process'){

            $request->flash();
            
            $customer = Input::get('customers');
            $template = Input::get('templates');
            $cus = $customer;

            if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){                
                
                $use = PostedAudit::getUsers($auth_user); 
                $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');                
            }
            if($role->role_id === 3){

                $temp = PostedAudit::getTemplatesMT($auth_user);            
                $use = $temp;
                $customers = PostedAudit::getCustomersMT($temp)->lists('customer','customer_code');               
            }

            $audits = PostedAudit::getAudits()->lists('description','audit_id');
	    	$templates = PostedAudit::getstemplatefilters($auth_user,$cus)->lists('template','channel_code');            
            $categories = FormCategory::OsaCatFilter($customer,$template,$use)->lists('category','category');

	    	return view('osareport.index', compact('audits','templates', 'skus', 'customers', 'categories'));
            
        }else{
            set_time_limit(0);
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('Per SKU OSA Report.csv');
            $writer->addRow(['audit_month', 'customers', 'audit_template', 'category', 'sku', 'store_count', 'availability', 'osa']); 

            foreach($skus as $row)
            {
                $row_data[0] = $row->description;
                $row_data[1] = $row->customer;       
                $row_data[2] = $row->template;
                $row_data[3] = $row->category;
                $row_data[4] = $row->prompt;
                $row_data[5] = $row->store_count;
                $row_data[6] = $row->availability;
                $row_data[7] = number_format($row->osa_percent,2);
                $writer->addRow($row_data); // add multiple rows at a time
            }   
            $writer->close();
        }
    }    
    public function categoryfilter(){


        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);

        $customer = Input::get('customers');
        $template = Input::get('templates');

        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

            $use = PostedAudit::getUsers($auth_user);   

        }
        if($role->role_id === 3){

            $temp = PostedAudit::getTemplatesMT($auth_user);            
            $use = $temp;    
        }
        
        $categories = FormCategory::OsaCatFilter($customer,$template,$use)->lists('category','category');

        return Response::json($categories);        
            
    }    

    public function monthfilter(){

        $auth_user = Auth::id();
        $customer = Input::get('customers');
        $template = Input::get('templates');
        $category = Input::get('categories');

        $id = $auth_user;
        $role = Role::myroleid($id);

        $audits = PostedAudit::getauditfiltersAFPlano($customer,$template,$category)->lists('description','audit_id');

        return Response::json($audits);

    }   

    public function getstoresinOSA(Request $request){

        $details = $request->all();        

        if(is_array($details)){
            $osaStore = PostedAudit::OsaStoresNotAvail($details);            
            return view('osareport.show',compact('osaStore','details'));
        }        
    }   

}
