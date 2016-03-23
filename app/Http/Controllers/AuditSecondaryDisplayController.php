<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Http\Requests;
use App\Audit;
use App\AuditSecondaryDisplay;
use App\AuditSecondaryDisplayLookup;


class AuditSecondaryDisplayController extends Controller
{
    public function index($id)
    {
        $audit = Audit::findOrFail($id);
        $stores = AuditSecondaryDisplayLookup::getStoresByAudit($audit);
        return view('auditsecondary.index',compact('audit', 'stores'));
    }

    public function create($id){
    	$audit = Audit::findOrFail($id);
    	return view('auditsecondary.create',compact('audit'));
    }

    public function store(Request $request, $id){
    	if ($request->hasFile('file')){
            $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());
           	$audit = Audit::findOrFail($id);
           	AuditSecondaryDisplay::createSecondaryDisplay($audit,$file_path);
           
            if (\File::exists($file_path))
            {
                \File::delete($file_path);
            }
            Session::flash('flash_message', 'Secondary Display successfully uploaded.');
            Session::flash('flash_class', 'alert-success');
            return redirect()->route("audits.secondarydisplay",$id);  		   	
		}else{
            Session::flash('flash_message', 'Error uploading masterfile.');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->route("audits.uploadsecondarydisplay",$id);
        }
				
		
    }
}
