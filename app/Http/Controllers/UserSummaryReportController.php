<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;
use App\PostedAuditCategorySummary;
use App\Audit;
use App\User;
use App\UserSummary;

class UserSummaryReportController extends Controller
{
    public function index(){
    	$users = PostedAudit::getUsers()->lists('name','user_id');
    	$audits = PostedAudit::getAudits()->lists('description','audit_id');
    	$user_summaries = PostedAudit::getUserSummary();
        // dd($user_summaries);
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
        $usersummary = UserSummary::getSummary($audit,$user);

    	$detail = $usersummary->detail;
    	$stores = $usersummary->stores;

    	return view('usersummaryreport.show', compact('detail', 'stores'));
    }
}
