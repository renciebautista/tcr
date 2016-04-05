<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\PostedAudit;

class AuditReportController extends Controller
{
    public function index(){
    	
    	$audits = PostedAudit::orderBy('updated_at', 'desc')->get();
    	return view('auditreport.index',compact('audits'));
    }
}
