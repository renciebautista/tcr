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

  	public static function getMultipleStoreDetails($stores,$take,$skip){
    	return self::select('audits.description as audit_description', 'customer', 'template', 'region', 'distributor', 'store_code', 'store_name', 
      		'posted_audits.created_at', 'category', 'group', 'prompt', 'answer')
      		->join('posted_audits', 'posted_audits.id', '=', 'posted_audit_details.posted_audit_id')
      		->join('audits', 'audits.id', '=', 'posted_audits.audit_id')
      		->whereIn('posted_audit_id', $stores)
      		->orderBy('posted_audit_details.id')
          ->skip($skip*$take)
          ->take($take)
      		->get();
  	}
}
