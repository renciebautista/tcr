<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditOsaLookupDetail extends Model
{
	public $timestamps = false;
    protected $fillable = ['audit_id', 'audit_osa_lookup_id', 'form_category_id', 'target'];
}
