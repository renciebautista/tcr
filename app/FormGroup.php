<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormGroup extends Model
{
    protected $fillable = ['audit_id','audit_template_id', 'group_desc'];
    public $timestamps = false;
}
