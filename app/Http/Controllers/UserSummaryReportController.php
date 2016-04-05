<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;

class UserSummaryReportController extends Controller
{
    public function index(){
    	$user_summaries = PostedAudit::getUserSummary();
    	return view('usersummaryreport.index', compact('user_summaries'));
    }

    public function show($audit_id,$user_id){
    	$detail = PostedAudit::getUserSummaryDetails($audit_id,$user_id);
    	$stores = PostedAudit::getStores($audit_id,$user_id);
    	return view('usersummaryreport.show', compact('detail', 'stores'));
    }
}
