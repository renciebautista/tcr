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
}
