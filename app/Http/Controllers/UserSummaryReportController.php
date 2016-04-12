<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;
use App\PostedAuditCategorySummary;

class UserSummaryReportController extends Controller
{
    public function index(){
    	$users = PostedAudit::getUsers()->lists('name','user_id');
    	$audits = PostedAudit::getAudits()->lists('description','audit_id');
    	$user_summaries = PostedAudit::getUserSummary();
    	return view('usersummaryreport.index', compact('user_summaries','users','audits'));
    }

    public function create(Request $request){
        $request->flash();
        $users = PostedAudit::getUsers()->lists('name','user_id');
        $audits = PostedAudit::getAudits()->lists('description','audit_id');
        $user_summaries = PostedAudit::getUserSummary($request);
        return view('usersummaryreport.index', compact('user_summaries','users','audits'));
    }

    public function show($audit_id,$user_id){
    	$detail = PostedAudit::getUserSummaryDetails($audit_id,$user_id);
    	$stores = PostedAudit::getStores($audit_id,$user_id);

        $posted_audit_ids = [];
        foreach ($stores as $store) {
           $posted_audit_ids[] = $store->id;
        }


        // $passed_groups = PostedAuditCategorySummary::where('passed',1)
        //     ->whereIn('posted_audit_id',$posted_audit_ids)
        //     ->get();

        // dd($passed_groups);

        
    	return view('usersummaryreport.show', compact('detail', 'stores'));
    }
}
