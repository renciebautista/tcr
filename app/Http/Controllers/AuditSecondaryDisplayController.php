<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Http\Requests;
use App\Audit;
use App\AuditSecondaryDisplay;
use App\AuditSecondaryDisplayLookup;
use App\AuditStore;
use App\FormCategory;


class AuditSecondaryDisplayController extends Controller
{
    public function index($audit_id)
    {
        $audit = Audit::findOrFail($audit_id);
        $stores = AuditSecondaryDisplayLookup::getStoresByAudit($audit);
        return view('auditsecondary.index',compact('audit', 'stores'));
    }

    public function create($audit_id){
    	$audit = Audit::findOrFail($audit_id);
    	return view('auditsecondary.create',compact('audit'));
    }

    public function store(Request $request, $audit_id){
    	if ($request->hasFile('file')){
            $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());
           	$audit = Audit::findOrFail($audit_id);
           	AuditSecondaryDisplay::createSecondaryDisplay($audit,$file_path);
           
            if (\File::exists($file_path))
            {
                \File::delete($file_path);
            }
            Session::flash('flash_message', 'Secondary Display successfully uploaded.');
            Session::flash('flash_class', 'alert-success');
            return redirect()->route("audits.secondarydisplay",$audit);  		   	
		}else{
            Session::flash('flash_message', 'Error uploading masterfile.');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->route("audits.uploadsecondarydisplay",$audit);
        }
    }

    public function edit($audit_id,$id){
        $audit = Audit::findOrFail($audit_id);
        $store = AuditStore::findOrFail($id);
        $categories = FormCategory::secondaryCategories($audit);
        $selected = AuditSecondaryDisplayLookup::getSelected($audit,$store);
        return view('auditsecondary.edit',compact('audit', 'store', 'categories', 'selected'));
    }

    public function update(Request $request, $audit_id,$id){
        $audit = Audit::findOrFail($audit_id);
        $store = AuditStore::findOrFail($id);
        $this->validate($request, [
            'brands' => 'required'
        ]);

        \DB::beginTransaction();

        try {
            AuditSecondaryDisplayLookup::where('audit_id',$audit->id)->where('audit_store_id',$store->id)->delete();
            foreach ($request->brands as $value) {
                AuditSecondaryDisplayLookup::create(['audit_id' => $audit->id,
                    'audit_store_id' => $store->id,
                    'secondary_display_id' => $value ]);
            }

            \DB::commit();

            Session::flash('flash_message', 'Secondary Display Lookup successfully updated!');
            Session::flash('flash_class', 'alert-success');
            return redirect()->route("audits.secondarydisplay_details",['audit' => $audit->id, 'id' => $store->id]);

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back();
        }
    }
}
