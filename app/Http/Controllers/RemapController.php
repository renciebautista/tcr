<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\AuditStore;
use App\PostedAudit;
use Session;
use Redirect;
use Auth;

class RemapController extends Controller
{
    public function index(){
    	$pagetitle = "Remap User";
    	$auth_user = Auth::id();
    	$users = User::allUsers()->lists('name','id');
    	return view('remap.index',compact('pagetitle','users'));
    }
    public function store(Request $request){

    	$old = strtoupper($request->get('old_name'));
    	$new = strtoupper($request->get('new_name'));

    	$name = User::where('id',$old)->first();
    	$new_name = User::where('id',$new)->first();
    	
    	$user = User::where('name',$name->name)->first();
        $user_id = $user->id;

    	if(!empty($user)){

    		$new_user = User::where('name', $new_name->name)->first();
            $new_user_id = $new_user->id;
    		
            AuditStore::remapToNew($user_id,$new_user_id);
            
            PostedAudit::remapToNew($user_id, $new_user_id);                

    		Session::flash('flash_message', 'User Remapping Successful.');
			Session::flash('flash_class', 'alert-success');

			return redirect('remap');
    	}


    }
}
