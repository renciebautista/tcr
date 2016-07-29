<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;
use App\FormCategory;
use Auth;
use App\Role;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Input;
use Response;
use Session;

class SosReportController extends Controller
{
    public function index(){

        set_time_limit(0);

        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);   
        
        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

            $use = PostedAudit::getUsers($auth_user);
            $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');
            $templates = PostedAudit::getTemplates($use)->lists('template','channel_code');            
            $users = PostedAudit::getUsers($auth_user)->lists('name','user_id');
            $stores = PostedAudit::getPostedStores($use)->lists('store_name','store_code');            
            $categories = FormCategory::getSOSCategories($use)->lists('category','category');                
            
        }    
       
        if($role->role_id === 3){  

            $temp = PostedAudit::getTemplatesMT($auth_user);
            $customers = PostedAudit::getCustomersMT($temp)->lists('customer','customer_code');
            $templates = PostedAudit::getTemplatesMT($auth_user)->lists('template','channel_code'); 
            $users = PostedAudit::getUsersMT($temp)->lists('name','user_id');                    
            $stores = PostedAudit::getPostedStoresMT($temp)->lists('store_name','store_code');
            $categories = FormCategory::getSOSCategoriesMT($temp)->lists('category','category'); 
        }
        
    	$audits = PostedAudit::getAudits()->lists('description','audit_id');
    	$soss = [];

    	return view('sosreport.index', compact('soss', 'audits', 'stores', 'customers', 'categories', 'templates','users'));
    }

    public function create(Request $request){
         set_time_limit(0);
        $auth_user = Auth::id();   
        $id = $auth_user;
        $role = Role::myroleid($id);   

        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){
            
            $use = PostedAudit::getUsers($auth_user);             
        }  

        if($role->role_id === 3){

            $temp = PostedAudit::getTemplatesMT($auth_user);
            $use = $temp;            
        }      
        $soss = PostedAudit::getSos($request,$use);
        
        
        if($request->submit == 'process'){
            $request->flash();

            if($role->role_id == 1 || $role->role_id == 2 || $role->role_id === 4){

                $customer = $request->get('customers');
                $cus = $customer;
                $template = $request->get('templates');
                $user = $request->get('users');
                $store = $request->get('stores');
                $category = $request->get('categories');
                $month = $request->get('audits');

                $use = PostedAudit::getUsers($auth_user);

                $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');               
                $templates = PostedAudit::getstemplatefilters($auth_user,$cus)->lists('template','channel_code');
                $users = PostedAudit::getUserAF($auth_user,$template,$customer)->lists('name','user_id');
                $stores = PostedAudit::getStoresfilterAF($customer,$template,$user)->lists('store_name','store_code');
                $categories = FormCategory::SosCatFilter($customer,$template,$user,$store,$use)->lists('category','category');
                $audits = PostedAudit::getauditfiltersAFSos($customer,$template,$user,$store,$category)->lists('description','audit_id');
            
            }

            if($role->role_id === 3){                                            
                            
                $customer = $request->get('customers');
                $cus = $customer;
                $template = $request->get('templates');
                $user = $request->get('users');
                $store = $request->get('stores');
                $month = $request->get('audits');
                $category = $request->get('categories');

                $temp = PostedAudit::getTemplatesMT($auth_user);            
                $use = $temp; 

                $customers = PostedAudit::getCustomersMT($temp)->lists('customer','customer_code');
                $templates = PostedAudit::getstemplatefilters($auth_user,$cus)->lists('template','channel_code');
                $users = PostedAudit::getUserAF($auth_user,$template,$customer)->lists('name','user_id');
                $stores = PostedAudit::getStoresfilterAF($customer,$template,$user)->lists('store_name','store_code');
                $categories = FormCategory::SosCatFilter($customer,$template,$user,$store,$use)->lists('category','category');
                $audits = PostedAudit::getauditfiltersAFSos($customer,$template,$user,$store,$category)->lists('description','audit_id');
            }
                       
            return view('sosreport.index', compact('soss', 'audits', 'stores', 'customers', 'categories', 'templates', 'users'));
        }else{
            set_time_limit(0);
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('SOS Report.csv');
            $writer->addRow(['audit_month', 'customers', 'audit_template', 'user', 'store_name', 'category', 'target', 'ps_sos_measurement', 'achievement']); 

            foreach($soss as $row)
            {
                $row_data[0] = $row->description;
                $row_data[1] = $row->customer;
                $row_data[2] = $row->template;
                $row_data[3] = $row->name;
                $row_data[4] = $row->store_name;
                $row_data[5] = $row->category;
                $row_data[6] = number_format($row->target,2);
                if($row->sos_measurement != ''){
                    $row_data[7] = number_format($row->sos_measurement,2);
                }
                else{
                    $row_data[7] = '';
                }
                if($row->sos_measurement >= $row->target){
                    $row_data[8] = 'true';
                }
                else{
                    $row_data[8] = 'false';
                }
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
        $user = Input::get('users');
        $store = Input::get('stores');


        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

            $use = PostedAudit::getUsers($auth_user);             
        }
        
        if($role->role_id === 3){

            $temp = PostedAudit::getTemplatesMT($auth_user);            
            $use = $temp;    
        }

        $categories = FormCategory::SosCatFilter($customer,$template,$user,$store,$use)->lists('category','category');

        return Response::json($categories);        
    }

    public function monthfilter(){

        $auth_user = Auth::id();
        $customer = Input::get('customers');
        $template = Input::get('templates');
        $user = Input::get('users');
        $store  = Input::get('stores');
        $category = Input::get('categories');

        $id = $auth_user;
        $role = Role::myroleid($id);

        $audits = PostedAudit::getauditfiltersAFSos($customer,$template,$user,$store,$category)->lists('description','audit_id');

        return Response::json($audits);

    }        
            
    
}
