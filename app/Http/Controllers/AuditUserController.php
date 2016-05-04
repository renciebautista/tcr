<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Audit;
use App\User;
use App\AuditStore;

class AuditUserController extends Controller
{
    public function index($id){
    	$audit = Audit::findOrFail($id);
    	$users = User::auditUsers($audit);
    	return view('audituser.index', compact('audit', 'users'));
    }

    public function mappedstores($audit_id,$id){
    	$audit = Audit::findOrFail($audit_id);
    	$user = User::findOrFail($id);
    	$stores = AuditStore::where('audit_id', $audit->id)->where('user_id', $user->id)->get();
    	return view('audituser.mappedstores', compact('audit', 'stores', 'user'));
    }
}
