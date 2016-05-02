<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\PostedAudit;
use App\PostedAuditDetail;

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
        $request->flash();
        $users = PostedAudit::getUsers()->lists('name','user_id');
        $audits = PostedAudit::getAudits()->lists('description','audit_id');
        $stores = PostedAudit::getPostedStores()->lists('store_name','store_code');
        $posted_audits = PostedAudit::search($request);
        return view('auditreport.index',compact('posted_audits','users','audits', 'status','stores'));
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
