<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;
use App\PostedAuditCategorySummary;
use App\Audit;
use App\User;

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
        $audit = Audit::findOrFail($audit_id);
        $user = User::findOrFail($user_id);
    	$detail = PostedAudit::getUserSummaryDetails($audit_id,$user_id);
    	$stores = PostedAudit::getStores($audit_id,$user_id);
        
        $doors = PostedAuditCategorySummary::getCategoryDoorsCount($audit,$user);
        
        $category_doors = $stores->count() * $doors['perfect_count'];
        $category_door_per = ($category_doors / ( $stores->count() * $doors['total']) * 100 );

    	return view('usersummaryreport.show', compact('detail', 'stores', 'category_doors', 'category_door_per'));
    }
}
