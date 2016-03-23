<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditMultiSelect extends Model
{
    protected $fillable = ['audit_template_id', 'option'];
}
