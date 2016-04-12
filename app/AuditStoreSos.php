<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditStoreSos extends Model
{
	protected $table ="audit_store_sos";
    protected $fillable = ['audit_id', 'audit_store_id', 'form_category_id', 'sos_type_id', 'audit_sos_lookup_id'];
   	public $timestamps = false;
}
