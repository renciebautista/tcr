<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostedAuditDetail extends Model
{
    public static function getDetails($id){
    	return self::select('customer', 'template', 'region', 'distributor', 'store_code', 'store_name', 
      		'created_at', 'category', 'group', 'prompt', 'answer')
      		->join('posted_audits', 'posted_audits.id', '=', 'posted_audit_details.posted_audit_id')
      		->where('posted_audit_id', $id)
      		->orderBy('posted_audit_details.id')
      		->get();
  	}
}
