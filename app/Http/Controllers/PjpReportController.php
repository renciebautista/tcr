<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\CheckIn;
use App\PostedAudit;

class PjpReportController extends Controller
{
    public function index(){
    	$users = PostedAudit::getUsers()->lists('name','user_id');
        $audits = PostedAudit::getAudits()->lists('description','audit_id');
    	$checkins = [];
    	return view('pjpreport.frequency', compact('checkins', 'users', 'audits'));
    }

    public function create(Request $request){
    	$request->flash();
    	$users = PostedAudit::getUsers()->lists('name','user_id');
        $audits = PostedAudit::getAudits()->lists('description','audit_id');
    	$checkins = CheckIn::search($request);
    	return view('pjpreport.frequency', compact('checkins', 'users', 'audits'));
    }
}
