<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Http\Requests;
use App\Audit;
use App\AuditOsaLookup;
use App\AuditStore;
use App\FormCategory;
use App\AuditOsaLookupDetail;
use App\UpdatedHash;

class AuditOsaTargetController extends Controller
{
    public function index($audit_id)
    {
        $audit = Audit::findOrFail($audit_id);
        $osalookups = AuditOsaLookup::getOsaLookupsByAudit($audit);
        return view('osatarget.index',compact('audit', 'osalookups'));
    }

    public function create($audit_id){
    	$audit = Audit::findOrFail($audit_id);
    	return view('osatarget.create',compact('audit'));
    }

    public function store(Request $request, $audit_id){
    	if ($request->hasFile('file')){
            $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());
           	$audit = Audit::findOrFail($audit_id);
           	
           	AuditOsaLookup::createOsaLookup($audit,$file_path);
           
            if (\File::exists($file_path))
            {
                \File::delete($file_path);
            }
            $hash = UpdatedHash::find(1);
            if(empty($hash)){
                UpdatedHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
            }else{
                $hash->hash = md5(date('Y-m-d H:i:s'));
                $hash->update();
            }

            Session::flash('flash_message', 'OSA Targets successfully uploaded.');
            Session::flash('flash_class', 'alert-success');
            return redirect()->route("audits.osatargets",$audit_id);  		   	
		}else{
            Session::flash('flash_message', 'Error uploading masterfile.');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->route("audits.uploadosatargets",$audit_id);
        }
    }

    public function edit($audit_id,$id){
        $audit = Audit::findOrFail($audit_id);
        $customers = AuditStore::getCustomerLists($audit);
        $regions = AuditStore::getRegionLists($audit);
        $distributors = AuditStore::getDistributorLists($audit);
        $templates = AuditStore::getTemplateLists($audit);
        $stores = AuditStore::getStoreLists($audit);
        $categories = FormCategory::osaCategories($audit);
        $lookup = AuditOsaLookup::with('categories')->findOrFail($id);
        return view('osatarget.edit',compact('audit', 'customers', 'regions', 'distributors', 'templates', 'stores', 'categories', 'lookup'));
    }

    public function update(Request $request, $audit_id, $id){
        $audit = Audit::findOrFail($audit_id);
        $lookup = AuditOsaLookup::findOrFail($id);
        $messages = [
            'unique_with' => 'This combination of selection already exists.',
        ];
        $this->validate($request, [
            'audit_id' => 'required',
            'customers' => 'required|unique_with:audit_osa_lookups, customers = customer_code, regions = region_code, distributors = distributor_code, stores = store_code, templates = channel_code,'.$id,
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

            AuditOsaLookupDetail::where('audit_osa_lookup_id',$lookup->id)->delete();
            foreach ($request->target as $category_id => $value) {
                if($category_id > 0){
                    if(!empty($value)){
                        $newlookup = new AuditOsaLookupDetail;
                        $newlookup->audit_id = $audit->id;
                        $newlookup->audit_osa_lookup_id = $lookup->id;
                        $newlookup->form_category_id = $category_id;
                        $newlookup->target = $value;
                        $newlookup->save();
                    }
                    
                } 
            }

            \DB::commit();

            $hash = UpdatedHash::find(1);
            if(empty($hash)){
                UpdatedHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
            }else{
                $hash->hash = md5(date('Y-m-d H:i:s'));
                $hash->update();
            }


            Session::flash('flash_message', 'OSA Lookup successfully updated!');
            Session::flash('flash_class', 'alert-success');
            return redirect()->route("audits.osatargets_details",['audit_id' => $audit->id, 'id' => $lookup->id]);

        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('flash_message', 'Error occured while updating OSA Lookups');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id){
        $lookup = AuditOsaLookup::findOrFail($id);
        \DB::beginTransaction();

        try {
            AuditOsaLookupDetail::where('audit_osa_lookup_id',$lookup->id)->delete();

            $lookup->delete();

            \DB::commit();

            $hash = UpdatedHash::find(1);
            if(empty($hash)){
                UpdatedHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
            }else{
                $hash->hash = md5(date('Y-m-d H:i:s'));
                $hash->update();
            }


            Session::flash('flash_message', 'OSA Lookup successfully deleted!');
            Session::flash('flash_class', 'alert-success');
           return redirect()->back();

        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('flash_message', 'Error occured while deleting OSA Lookups');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->back();
        }
    }
}
