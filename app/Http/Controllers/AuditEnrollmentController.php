<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\AuditEnrollmentTypeMapping;
use App\Audit;

class AuditEnrollmentController extends Controller
{
    public function index($audit_id){
    	$audit = Audit::findOrFail($audit_id);
    	$enrollments = AuditEnrollmentTypeMapping::where('audit_id',$audit->id)->get();
    	return view('auditenrollment.index', compact('audit', 'enrollments'));
    }
}
