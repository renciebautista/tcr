<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\FormCategory;
use App\FormGroup;
use App\PostedAudit;
use App\PostedAuditCategorySummary;
use App\AuditTemplate;

class StoreSummaryReportController extends Controller
{
    public function show($id){

    	$store = PostedAudit::findOrFail($id);
    	$data = PostedAuditCategorySummary::getCategorySummary($store->id);

    	$template = AuditTemplate::where('audit_id',$store->audit_id)
    		->where('description',$store->template)
    		->first();

    	$categories =  FormCategory::getTemplateCategory($template->id,$store->audit_id);

    	$groups = FormGroup::getTemplateGroup($template->id,$store->audit_id);

    	foreach ($categories as $category) {
            if($category->perfect_store){
                $lastvalue = 1;
                foreach ($groups as $group) {
                    if($group->perfect_store){
                       if(isset($data[$category->category][$group->group_desc])){
                            $lastvalue = $data[$category->category][$group->group_desc] && $lastvalue;
                        } 
                    }
                    
                }
                $data[$category->category]['PERFECT STORE']  = $lastvalue;
            }
    		
		}

    	return view('storesummary.show',compact('store', 'categories', 'groups', 'data'));
    }
}
