<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditTemplateCategory extends Model
{
    public $timestamps = false;
    protected $fillable = ['audit_template_id', 'form_category_id', 'category_order'];

    public static function getLastOrder($id){
    	return self::where('audit_template_id',$id)
			->groupBy('category_order')
			->orderBy('category_order', 'desc')->first();
    }

    public static function categoryExist($template_id, $category_id){
    	return self::where('audit_template_id',$template_id)
			->where('form_category_id',$category_id)->first();
    }
}
