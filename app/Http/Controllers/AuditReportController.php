<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\PostedAudit;
use App\PostedAuditDetail;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Input;
use Response;
class AuditReportController extends Controller
{
    public function index(){
        $auth_user = Auth::id();
        $users = PostedAudit::getUsers($auth_user)->lists('name','user_id');
        $use = PostedAudit::getUsers($auth_user);        
        $templates = PostedAudit::getTemplates($auth_user)->lists('template','channel_code');
        $audits = PostedAudit::getAudits()->lists('description','audit_id');
        $stores = PostedAudit::getPostedStores($use)->lists('store_name','store_code');
        $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');
    	$posted_audits = PostedAudit::searchDefault($use);                
        $p_store_average = PostedAudit::getPerfectStoreAverage($posted_audits);
        $osa_average = PostedAudit::getOsaAverage($posted_audits);
        $npi_average = PostedAudit::getNpiAverage($posted_audits);
        $planogram_average = PostedAudit::getPlanogramAverage($posted_audits);
    	return view('auditreport.index',compact('posted_audits','users','audits', 'stores', 'customers','p_store_average','osa_average','npi_average','planogram_average','templates'));
    }

    public function create(Request $request){
        $auth_user = Auth::id();        
        // dd($request->all());
        $usse = PostedAudit::getUsers($auth_user);
        $posted_audits = PostedAudit::search($request,$usse);
        if($request->submit == 'process'){
            $request->flash();
            $users = PostedAudit::getUsers($auth_user)->lists('name','user_id');
            $use = PostedAudit::getUsers($auth_user);
            $templates = PostedAudit::getTemplates($auth_user)->lists('template','channel_code');
            $audits = PostedAudit::getAudits()->lists('description','audit_id');
            $stores = PostedAudit::getPostedStores($use)->lists('store_name','store_code');
            $customers = PostedAudit::getCustomers($use)->lists('customer','customer_code');
            $p_store_average = PostedAudit::getPerfectStoreAverage($posted_audits);
            $osa_average = PostedAudit::getOsaAverage($posted_audits);
            $npi_average = PostedAudit::getNpiAverage($posted_audits);
            $planogram_average = PostedAudit::getPlanogramAverage($posted_audits);
            return view('auditreport.index',compact('posted_audits','users','audits', 'status','stores','customers','p_store_average','osa_average','npi_average','planogram_average','templates'));
        }else{
            set_time_limit(0);
            $writer = WriterFactory::create(Type::CSV);
            $writer->openToBrowser('Posted Audit Report.csv');
            $writer->addRow(['audit_description','user',
                'customer', 'template', 'region', 'distributor', 'store_code', 'store_name','created_at',
                'category', 'group', 'prompt', 'answer']); 

            $store_id_array = [];
            foreach ($posted_audits as $posted_audit) {
                $store_id_array[] = $posted_audit->id;
            }

            $take = 1000; // adjust this however you choose
            $skip = 0; // used to skip over the ones you've already processed

            while($rows = PostedAuditDetail::getMultipleStoreDetails($store_id_array,$take,$skip)){
                if(count($rows) == 0){
                    break;
                }
                $skip ++;
                $plunck_data = [];

                foreach($rows as $row)
                {

                    $row_data[0] = $row->audit_description;
                    $row_data[1] = $row->name;
                    $row_data[2] = $row->customer;
                    $row_data[3] = $row->template;
                    $row_data[4] = $row->region;
                    $row_data[5] = $row->distributor;
                    $row_data[6] = $row->store_code;
                    $row_data[7] = $row->created_at;
                    $row_data[8] = $row->category;
                    $row_data[9] = $row->group;
                    $row_data[10] = $row->prompt;
                    $row_data[11] = $row->answer;
                    $plunck_data[] = $row_data;
                }

                $writer->addRows($plunck_data); // add multiple rows at a time
            }
            
            
            $writer->close();

            
        }
        
    }

    public function download($id){
    	$post_audit = PostedAudit::findOrFail($id);
        $details = PostedAuditDetail::getDetails($post_audit->id);

        foreach ($details as $key => $value) {
            $img = explode(".", $value->answer);
            if((isset($img[1])) && (strtolower($img[1]) == "jpg")){
                $link = url('auditimage/'.$id.'/'.$value->answer);
                $value->answer = $link;
            }
            
        }
        
        \Excel::create($post_audit->store_name . ' - '. $post_audit->template, function($excel) use ($details) {
            $excel->sheet('Sheet1', function($sheet) use ($details) {
                $sheet->fromModel($details,null, 'A1', true);
            })->download('xls');

        });
    }

    public function filter(){
        $auth_user = Auth::id();
        $cus = Input::all();        
        if(is_array($cus)){            
            $users = PostedAudit::getsfilters($auth_user,$cus)->lists('name','user_id');
            return Response::json($users);
        }            
        
    }  
    public function alluserfilter(){
        $auth_user = Auth::id();        
            $users = PostedAudit::getUsers($auth_user)->lists('name','user_id');
            return Response::json($users);                
    }    
    public function storefilter(){
        $auth_user = Auth::id();
        $cus = Input::all();        
        if(is_array($cus)){           
            $use = PostedAudit::getUsers($auth_user);         
            $stores = PostedAudit::getPostedStores($use)->lists('store_name','store_code');
            return Response::json($stores);
        }                    
    }    
    public function allstorefilter(){
        $auth_user = Auth::id();
        $cus = Input::all();        
        if(is_array($cus)){           
            $use = PostedAudit::getUsers($auth_user);         
            $stores = PostedAudit::getsstorefilters($cus,$use)->lists('store_name','store_code');
            return Response::json($stores);
        }                    
    }    
    public function templatesfilter(){
        $auth_user = Auth::id();
        $cus = Input::all();        
        if(is_array($cus)){           
            $use = PostedAudit::getUsers($auth_user);         
            $templates = PostedAudit::getstemplatefilters($auth_user,$cus)->lists('template','channel_code');
            return Response::json($templates);
        }                    
    }    
    public function alltemplatesfilter(){
        $auth_user = Auth::id();
        $cus = Input::all();        
        if(is_array($cus)){           
            $use = PostedAudit::getUsers($auth_user);         
            $templates = PostedAudit::getTemplates($auth_user)->lists('template','channel_code');
            return Response::json($templates);
        }                    
    }    
}
