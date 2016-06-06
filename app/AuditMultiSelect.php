<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditMultiSelect extends Model
{
	public $timestamps = false;
    protected $fillable = ['audit_template_id', 'option'];
}
