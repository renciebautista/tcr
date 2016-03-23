<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormFormula extends Model
{
    protected $fillable = ['audit_template_id', 'form_id', 'formula', 'formula_desc'];
    public $timestamps = false;
}
