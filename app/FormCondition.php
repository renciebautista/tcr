<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormCondition extends Model
{
    protected $fillable = ['audit_template_id', 'form_id', 'option', 'condition', 'condition_desc'];
    public $timestamps = false;
}
