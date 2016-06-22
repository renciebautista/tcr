<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Audit;
use App\User;
use App\AuditStore;
use Session;
use App\AuditUserPjp;

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
    	$stores = AuditStore::where('audit_id', $audit->id)->where('user_id', $user->id)
    		->orderBy('customer')
    		->orderBy('store_name')
    		->get();
    	return view('audituser.mappedstores', compact('audit', 'stores', 'user'));
    }

    public function create($id){
        $audit = Audit::findOrFail($id);
        return view('audituser.create',compact('audit'));
    }

    public function store(Request $request, $id){
        if ($request->hasFile('file')){
            $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());

            AuditUserPjp::import($id,$file_path);

            if (\File::exists($file_path))
            {
                \File::delete($file_path);
            }

            Session::flash('flash_message', 'Audit PJP Target successfully uploaded.');
            Session::flash('flash_class', 'alert-success');
            return redirect()->route("audits.users",$id); 

            
        }else{
            Session::flash('flash_message', 'Error uploading pjp target.');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->route("audits.uploadpjptarget",$id);
        }
    }
}
