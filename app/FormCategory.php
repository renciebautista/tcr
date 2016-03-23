<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormCategory extends Model
{
    protected $fillable = ['audit_id','audit_template_id', 'category'];
    public $timestamps = false;
}
