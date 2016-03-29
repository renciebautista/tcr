<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditSosLookupDetail extends Model
{
    public $timestamps = false;
    protected $fillable = ['audit_id', 'audit_sos_lookup_id', 'form_category_id', 'sos_type_id', 'less', 'value'];
}
