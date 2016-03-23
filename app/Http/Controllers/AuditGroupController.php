<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Audit;
use App\FormGroup;

class AuditGroupController extends Controller
{
    public function index($id){
    	$audit = Audit::findOrFail($id);
    	$groups = FormGroup::where('audit_id',$audit->id)->get();
    	return view('auditgroup.index', compact('audit', 'groups'));
    }
}
