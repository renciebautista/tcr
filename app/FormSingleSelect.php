<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormSingleSelect extends Model
{
	public $timestamps = false;
    protected $fillable = ['audit_template_id', 'form_id', 'audit_single_select_id'];
}
