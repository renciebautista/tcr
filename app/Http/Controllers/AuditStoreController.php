<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Http\Requests;
use App\Audit;
use App\AuditStore;

class AuditStoreController extends Controller
{
    public function index($id)
    {
        $audit = Audit::findOrFail($id);
        $stores = AuditStore::with('fielduser')->where('audit_id',$id)->get();
        return view('auditstore.index',compact('audit', 'stores'));
    }

    public function create($id){
    	$audit = Audit::findOrFail($id);
    	return view('auditstore.create',compact('audit'));
    }

    public function store(Request $request, $id){
    	if ($request->hasFile('file')){
            $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());
            \Excel::selectSheets('Sheet1')->load($file_path, function($reader) use ($id) {
                AuditStore::import($id,$reader->get());
            });

            if (\File::exists($file_path))
            {
                \File::delete($file_path);
            }

		   	Session::flash('flash_message', 'Store Masterfile successfully uploaded.');
			Session::flash('flash_class', 'alert-success');
            return redirect()->route("audits.stores",$id);    
		}else{
			Session::flash('flash_message', 'Error uploading masterfile.');
			Session::flash('flash_class', 'alert-danger');
			return redirect()->route("audits.uploadstores",$id);	
		}
		
    }
}
