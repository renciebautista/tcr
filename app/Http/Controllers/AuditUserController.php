<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Audit;
use App\User;

class AuditUserController extends Controller
{
    public function index($id){
    	$audit = Audit::findOrFail($id);
    	$users = User::auditUsers($audit);
    	return view('audituser.index', compact('audit', 'users'));
    }
}
