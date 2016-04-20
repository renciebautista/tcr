<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostedAuditCategorySummary extends Model
{
    public static function getCategorySummary($post_audit_id){
    	$records = self::where('posted_audit_id',$post_audit_id)->get();
    	$data = array();
    	foreach ($records as $record) {
    		$data[$record->category][$record->group] = $record->passed; 
    	}
    	return $data;
    }

    public static function getCategoryDoorsCount($audit,$user){
    	$cnt = 0;
    	$total = 0;
    	$posted_stores = PostedAudit::getStores($audit->id,$user->id);

    	$all_stores = [];
    	foreach ($posted_stores as $posted_store) {
    		$template = AuditTemplate::where('audit_id',$posted_store->audit_id)
	    		->where('description',$posted_store->template)
	    		->first();

	    	$categories =  FormCategory::getTemplateCategory($template->id,$posted_store->audit_id,1);
    		$groups = FormGroup::getTemplateGroup($template->id,$posted_store->audit_id,1);

    		$posted_categories = PostedAuditCategorySummary::getCategorySummary($posted_store->id);
    		$perfect_store = 0;
    		foreach ($categories as $category) {
	    		$lastvalue = 1;
	    		foreach ($groups as $group) {
	    			if(isset($posted_categories[$category->category][$group->group_desc])){
	    				$lastvalue = $posted_categories[$category->category][$group->group_desc] && $lastvalue;
	    			}
	    		}
	    		if($lastvalue == 1){
	    			$cnt++;
	    		}
	    		$total++;
	    		$all_stores[$posted_store->id][$category->category] = $lastvalue;
			}
	    	
    	}
    	$data['perfect_count'] = $cnt;
    	$data['total'] = $total;

    	return $data;
    	// dd($all_stores);

    	

    }
}
