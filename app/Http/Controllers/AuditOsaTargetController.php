<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Http\Requests;
use App\Audit;
use App\AuditOsaLookup;

class AuditOsaTargetController extends Controller
{
    public function index($id)
    {
        $audit = Audit::findOrFail($id);
        $osalookups = AuditOsaLookup::getOsaLookupsByAudit($audit);
        return view('osatarget.index',compact('audit', 'osalookups'));
    }

    public function create($id){
    	$audit = Audit::findOrFail($id);
    	return view('osatarget.create',compact('audit'));
    }

    public function store(Request $request, $id){
    	if ($request->hasFile('file')){
            $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());
           	$audit = Audit::findOrFail($id);
           	
           	AuditOsaLookup::createOsaLookup($audit,$file_path);
           
            if (\File::exists($file_path))
            {
                \File::delete($file_path);
            }
            Session::flash('flash_message', 'OSA Targets successfully uploaded.');
            Session::flash('flash_class', 'alert-success');
            return redirect()->route("audits.osatargets",$id);  		   	
		}else{
            Session::flash('flash_message', 'Error uploading masterfile.');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->route("audits.uploadosatargets",$id);
        }
    }
}
