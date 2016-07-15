<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;
use App\FormCategory;
use Auth;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Input;
use Response;
use Session;
class SosReportController extends Controller
{
    public function index(){
        $auth_user = Auth::id();
        $use = PostedAudit::getUsers($auth_user); 
    	$audits = PostedAudit::getAudits()->lists('description','audit_id');
    	$stores = PostedAudit::getPostedStores($use)->lists('store_name','store_code');
        $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');
        $categories = FormCategory::getSOSCategories($use)->lists('category','category');
        $templates = PostedAudit::getTemplates($auth_user)->lists('template','channel_code');
        $users = PostedAudit::getUsers($auth_user)->lists('name','user_id');
    	$soss = [];
    	return view('sosreport.index', compact('soss', 'audits', 'stores', 'customers', 'categories', 'templates','users'));
    }

    public function create(Request $request){
        $auth_user = Auth::id(); 
        $use = PostedAudit::getUsers($auth_user); 
        $soss = PostedAudit::getSos($request,$use);
        if($request->submit == 'process'){
            $request->flash();
            $audits = PostedAudit::getAudits()->lists('description','audit_id');
    		$stores = PostedAudit::getPostedStores($use)->lists('store_name','store_code');
            $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');
            $categories = FormCategory::getSOSCategories($use)->lists('category','category');
            $templates = PostedAudit::getTemplates($auth_user)->lists('template','channel_code');
            $users = PostedAudit::getUsers($auth_user)->lists('name','user_id');
            
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
    public function allcategoryfilter(){
        $auth_user = Auth::id();
        $tem = Input::all();

        if(is_array($tem)){           
            $use = PostedAudit::getUsers($auth_user);         
            $categories = FormCategory::getSOSCategories($use)->lists('category','category');
            return Response::json($categories);
        }                    
    }       
    public function categoryfilter(){
        $auth_user = Auth::id();
        $tem = Input::all();                
        if(is_array($tem)){           
            $use = PostedAudit::getUsers($auth_user);         
            $categories = FormCategory::SosCatFilter($use,$tem)->lists('category','category');
            return Response::json($categories);
        }                    
    }       
}
