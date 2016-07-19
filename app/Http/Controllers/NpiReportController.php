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

class NpiReportController extends Controller
{
    public function index(){
        $auth_user = Auth::id();
        $use = PostedAudit::getUsers($auth_user); 
    	$audits = PostedAudit::getAudits()->lists('description','audit_id');
    	$templates = PostedAudit::getTemplates($auth_user)->lists('template','channel_code');
        $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');
        $categories = FormCategory::getNPICategories($use)->lists('category','category');
    	$skus = [];
    	return view('npireport.index', compact('audits','templates', 'skus', 'customers', 'categories'));
    }

    public function create(Request $request){
        $auth_user = Auth::id();
        $use = PostedAudit::getUsers($auth_user); 
    	$skus = PostedAudit::getNpiSku($request,$use);
        if($request->submit == 'process'){
            $request->flash();
            $audits = PostedAudit::getAudits()->lists('description','audit_id');
	    	$templates = PostedAudit::getTemplates($auth_user)->lists('template','channel_code');
            $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');
            $categories = FormCategory::getNPICategories($use)->lists('category','category');
	    	return view('npireport.index', compact('audits','templates', 'skus', 'customers', 'categories'));
        }else{
            set_time_limit(0);
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('Per SKU NPI Report.csv');
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
    public function allcategoryfilter(){
        $auth_user = Auth::id();
        $tem = Input::all();

        if(is_array($tem)){           
            $use = PostedAudit::getUsers($auth_user);         
            $categories = FormCategory::getNPICategories($use)->lists('category','category');
            return Response::json($categories);
        }                    
    }       
    public function categoryfilter(){
        $auth_user = Auth::id();
        $tem = Input::all();                
        if(is_array($tem)){           
            $use = PostedAudit::getUsers($auth_user);         
            $categories = FormCategory::NpiCatFilter($use,$tem)->lists('category','category');
            return Response::json($categories);
        }                    
    }       
    public function getstoresinNPI(Request $request){
        $details = $request->all();
        if(is_array($details)){
            $npiStore = PostedAudit::NpiStoresNotAvail($details);            
            return view('npireport.show',compact('npiStore','details'));
        }        
    }   
}
