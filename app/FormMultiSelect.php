<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormMultiSelect extends Model
{
    public $timestamps = false;
    protected $fillable = ['audit_template_id', 'form_id', 'audit_multi_select_id'];
}
