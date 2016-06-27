<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\PostedAudit;
use App\PostedAuditDetail;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class AuditReportController extends Controller
{
    public function index(){
        $users = PostedAudit::getUsers()->lists('name','user_id');
        $audits = PostedAudit::getAudits()->lists('description','audit_id');
        $stores = PostedAudit::getPostedStores()->lists('store_name','store_code');
    	$posted_audits = PostedAudit::search([]);
    	return view('auditreport.index',compact('posted_audits','users','audits', 'stores'));
    }

    public function create(Request $request){
        $posted_audits = PostedAudit::search($request);
        if($request->submit == 'process'){
            $request->flash();
            $users = PostedAudit::getUsers()->lists('name','user_id');
            $audits = PostedAudit::getAudits()->lists('description','audit_id');
            $stores = PostedAudit::getPostedStores()->lists('store_name','store_code');
            
            return view('auditreport.index',compact('posted_audits','users','audits', 'status','stores'));
        }else{
            set_time_limit(0);
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('Posted Audit Report.csv');
            $writer->addRow(['audit_description', 
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
                    $row_data[1] = $row->customer;
                    $row_data[2] = $row->template;
                    $row_data[3] = $row->region;
                    $row_data[4] = $row->distributor;
                    $row_data[5] = $row->store_code;
                    $row_data[6] = $row->created_at;
                    $row_data[7] = $row->category;
                    $row_data[8] = $row->group;
                    $row_data[9] = $row->prompt;
                    $row_data[10] = $row->answer;
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
}
