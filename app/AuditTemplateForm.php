<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditTemplateForm extends Model
{
    public $timestamps = false;
    protected $fillable = ['audit_template_id', 'audit_template_group_id', 'form_id', 'order'];


    public static function getLastOrder($group_id){
		return self::where('audit_template_group_id',$group_id)
			->groupBy('order')
			->orderBy('order', 'desc')->first();
	}
}
