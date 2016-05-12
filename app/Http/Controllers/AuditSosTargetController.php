<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Http\Requests;
use App\Audit;
use App\AuditSosLookup;
use App\AuditStore;
use App\FormCategory;
use App\SosType;
use App\AuditSosLookupDetail;
use App\AuditStoreSos;

class AuditSosTargetController extends Controller
{
    public function index($id){
    	$audit = Audit::findOrFail($id);
        $soslookups = AuditSosLookup::getSosLookupsByAudit($audit);
        return view('sostarget.index',compact('audit', 'soslookups'));
    }

    public function create($id){
    	$audit = Audit::findOrFail($id);
    	return view('sostarget.create',compact('audit'));
    }

    public function store(Request $request, $id){
    	if ($request->hasFile('file')){
            $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());
           	$audit = Audit::findOrFail($id);
           	set_time_limit(0);
           	AuditSosLookup::createSosLookup($audit,$file_path);
           
            if (\File::exists($file_path))
            {
                \File::delete($file_path);
            }
            Session::flash('flash_message', 'SOS Targets successfully uploaded.');
            Session::flash('flash_class', 'alert-success');
            return redirect()->route("audits.sostargets",$id);  		   	
		}else{
            Session::flash('flash_message', 'Error uploading masterfile.');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->route("audits.uploadsostargets",$id);
        }
    }

    public function edit($audit_id,$id){
        $audit = Audit::findOrFail($audit_id);
        $customers = AuditStore::getCustomerLists($audit);
        $regions = AuditStore::getRegionLists($audit);
        $distributors = AuditStore::getDistributorLists($audit);
        $templates = AuditStore::getTemplateLists($audit);
        $stores = AuditStore::getStoreLists($audit);
        $categories = FormCategory::sosCategories($audit);
        $lookup = AuditSosLookup::with('categories')->findOrFail($id);
        $sos_types = SosType::all();
        return view('sostarget.edit',compact('audit', 'customers', 'regions', 'distributors', 'templates',
            'stores', 'categories', 'lookup', 'sos_types'));
    }

    public function update(Request $request, $audit_id, $id){
        $audit = Audit::findOrFail($audit_id);
        $lookup = AuditSosLookup::findOrFail($id);
        $messages = [
            'unique_with' => 'This combination of selection already exists.',
        ];
        $this->validate($request, [
            'audit_id' => 'required',
            'customers' => 'required|unique_with:audit_sos_lookups, customers = customer_code, regions = region_code, distributors = distributor_code, stores = store_code, templates = channel_code,'.$id,
            'regions' => 'required',
            'distributors' => 'required',
            'stores' => 'required',
            'templates' => 'required'
        ],$messages);

        \DB::beginTransaction();

        try {
            $lookup->customer_code = $request->customers;
            $lookup->region_code = $request->regions;
            $lookup->distributor_code = $request->distributors;
            $lookup->store_code = $request->stores;
            $lookup->channel_code = $request->templates;
            $lookup->update();

            AuditSosLookupDetail::where('audit_sos_lookup_id',$lookup->id)->delete();
            foreach ($request->category as $category_id => $category) {
                $less = $category[0];
                foreach ($category as $key => $value) {
                    if($key > 0){
                        if(!empty($value)){
                            $newlookup = new AuditSosLookupDetail;
                            $newlookup->audit_id = $audit->id;
                            $newlookup->audit_sos_lookup_id = $lookup->id;
                            $newlookup->form_category_id = $category_id;
                            $newlookup->sos_type_id = $key;
                            $newlookup->less = $less;
                            $newlookup->value = $value;
                            $newlookup->save();
                        }
                        
                    }
                } 
            }

            \DB::commit();

            Session::flash('flash_message', 'SOS Lookup successfully updated!');
            Session::flash('flash_class', 'alert-success');
            return redirect()->route("audits.sostargets_details",['audit_id' => $audit->id, 'id' => $lookup->id]);

        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('flash_message', 'Error occured while updating SOS Lookups');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id){
        $lookup = AuditSosLookup::findOrFail($id);
        \DB::beginTransaction();

        try {
            AuditStoreSos::where('audit_sos_lookup_id',$lookup->id)->delete();
            AuditSosLookupDetail::where('audit_sos_lookup_id',$lookup->id)->delete();

            $lookup->delete();

            \DB::commit();

            Session::flash('flash_message', 'SOS Lookup successfully deleted!');
            Session::flash('flash_class', 'alert-success');
           return redirect()->back();

        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('flash_message', 'Error occured while deleting SOS Lookups');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->back();
        }
    }
}
