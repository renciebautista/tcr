<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditSecondaryDisplayLookup extends Model
{
    protected $fillable = ['audit_id','audit_store_id', 'secondary_display_id'];
    public $timestamps = false;

    public static function getStoresByAudit($audit){
    	return self::select('audit_stores.id', 'audit_stores.store_code', 'audit_stores.store_name')
    		->join('audit_stores', 'audit_secondary_display_lookups.audit_store_id','=','audit_stores.id')
    		->where('audit_stores.audit_id', $audit->id)
    		->groupBy('store_name')
    		->orderBy('audit_secondary_display_lookups.id')
    		->get();
    }
}
