<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Http\Requests;
use App\Audit;
use App\AuditTemplate;

class AuditTemplateController extends Controller
{
    public function index($id){
    	$audit = Audit::findOrFail($id);
    	$templates = AuditTemplate::where('audit_id',$audit->id)->get();
    	return view('audittemplate.index', compact('audit', 'templates'));
    }

    public function create($id){
    	$audit = Audit::findOrFail($id);
    	return view('audittemplate.create',compact('audit'));
    }

    public function store(Request $request, $id){
    	if ($request->hasFile('file')){
            $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());

            $reply = AuditTemplate::import($id,$file_path);

            if (\File::exists($file_path))
            {
                \File::delete($file_path);
            }
            if($reply['status'] == 1){
                Session::flash('flash_message', 'Audit Template successfully uploaded.');
                Session::flash('flash_class', 'alert-success');
                return redirect()->route("audits.templates",$id);    
            }else{
                Session::flash('flash_message', 'Invalid file format');
                Session::flash('flash_class', 'alert-danger');
                return redirect()->route("audits.uploadtemplates",$id);
            }

		   	
		}else{
            Session::flash('flash_message', 'Error uploading masterfile.');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->route("audits.uploadtemplates",$id);
        }
    }

    public function categories($id){
        $categories = [];
        return view('audittemplate.categories',compact('categories'));
    }
}
