<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;

class CustomizedPlanogramReportController extends Controller
{
    public function index(){
    	$audits = PostedAudit::getAudits()->lists('description','audit_id');
    	$templates = PostedAudit::getTemplates()->lists('template','channel_code');
    	$skus = [];
    	return view('customizedplanoreport.index', compact('audits','templates', 'skus'));
    }

    public function create(Request $request){
    	$skus = PostedAudit::getCustomizedPlanoSku($request);
        if($request->submit == 'process'){
            $request->flash();
            $audits = PostedAudit::getAudits()->lists('description','audit_id');
	    	$templates = PostedAudit::getTemplates()->lists('template','channel_code');
	    	return view('customizedplanoreport.index', compact('audits','templates', 'skus'));
        }else{
            // $store_id_array = [];
            // foreach ($posted_audits as $posted_audit) {
            //     $store_id_array[] = $posted_audit->id;
            // }
            // $details = PostedAuditDetail::getMultipleStoreDetails($store_id_array);
            
            // \Excel::create('Posted Audit Report', function($excel) use ($details) {
            //     $excel->sheet('Sheet1', function($sheet) use ($details) {
            //         $sheet->fromModel($details,null, 'A1', true);
            //     })->download('xls');

            // });
        }
    }
}
