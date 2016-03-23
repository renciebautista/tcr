<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Audit;
use App\AuditUser;

class AuditUserController extends Controller
{
    public function index($id){
    	$audit = Audit::findOrFail($id);
    	$users = AuditUser::where('audit_id',$audit->id)->get();
    	return view('audituser.index', compact('audit', 'users'));
    }
}
