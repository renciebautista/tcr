<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Http\Requests;
use App\Audit;
use App\AuditStore;

class AuditController extends Controller
{
    public function index(){
    	$audits = Audit::all();
    	return view('audit.index', compact('audits'));
    }


    public function create()
    {
        return view('audit.create');
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|max:100',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        $audit = new Audit;
        $audit->description = $request->description;
        $audit->start_date = date('Y-m-d',strtotime($request->start_date));
        $audit->end_date = date('Y-m-d',strtotime($request->end_date));
        $audit->active = ($request->active) ? 1 : 0;
        $audit->save();

        Session::flash('flash_message', 'Audit successfully added!');
        Session::flash('flash_class', 'alert-success');
        return redirect()->route("audits.index");
    }


    public function stores($id)
    {
        $audit = Audit::findOrFail($id);
        $stores = AuditStore::all();
        return view('audit.show',compact('audit', 'stores'));
    }

    public function uploadstores($id){
    	$audit = Audit::findOrFail($id);
    	return view('audit.uploadstore',compact('audit'));
    }

    public function postuploadstores(Request $request, $id){
    	if ($request->hasFile('file')){
    		
		   	Session::flash('flash_message', 'Store Masterfile successfully uploaded.');
			Session::flash('flash_class', 'alert-success');
		}else{
			Session::flash('flash_message', 'Error uploading masterfile.');
			Session::flash('flash_class', 'alert-danger');
			return redirect()->route("audits.uploadstores",$id);	
		}
		
    }
}
