<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditSingleSelect extends Model
{
	public $timestamps = false;
    protected $fillable = ['audit_template_id', 'option'];
}
