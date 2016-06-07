<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;

class SosReportController extends Controller
{
    public function index(){
    	$audits = PostedAudit::getAudits()->lists('description','audit_id');
    	$stores = PostedAudit::getPostedStores()->lists('store_name','store_code');
    	$soss = PostedAudit::getSos();
    	return view('sosreport.index', compact('soss', 'audits', 'stores'));
    }

    public function create(Request $request){
        $soss = PostedAudit::getSos($request);
        if($request->submit == 'process'){
            $request->flash();
            $audits = PostedAudit::getAudits()->lists('description','audit_id');
    		$stores = PostedAudit::getPostedStores()->lists('store_name','store_code');
            
            return view('sosreport.index', compact('soss', 'audits', 'stores'));
        }else{
            $store_id_array = [];
            foreach ($posted_audits as $posted_audit) {
                $store_id_array[] = $posted_audit->id;
            }
            $details = PostedAuditDetail::getMultipleStoreDetails($store_id_array);
            
            \Excel::create('Posted Audit Report', function($excel) use ($details) {
                $excel->sheet('Sheet1', function($sheet) use ($details) {
                    $sheet->fromModel($details,null, 'A1', true);
                })->download('xls');

            });
        }
        
    }
}
