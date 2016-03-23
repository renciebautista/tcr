<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditTemplateGroup extends Model
{
    public $timestamps = false;
    protected $fillable = ['audit_template_id', 'audit_template_category_id', 'form_group_id', 'group_order'];

    public static function getLastOrder($id){
    	return self::where('audit_template_category_id',$id)
			->groupBy('group_order')
			->orderBy('group_order', 'desc')->first();
    }

    public static function categoryExist($category_id, $group_id){
    	return self::where('audit_template_category_id',$category_id)
			->where('form_group_id',$group_id)->first();
    }
}
