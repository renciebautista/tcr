<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormCategory extends Model
{
    protected $fillable = ['audit_id','audit_template_id', 'category'];
    public $timestamps = false;

    public function secondarybrand(){
        return $this->hasMany('App\AuditSecondaryDisplay', 'form_category_id', 'id');
    }

    public static function secondaryCategories($audit){
    	return self::with('secondarybrand')->where('audit_id', $audit->id)->where('second_display', 1)->get();
    }

    public static function osaCategories($audit){
    	return self::where('audit_id', $audit->id)->where('osa', 1)->get();
    }

    public static function sosCategories($audit){
        return self::where('audit_id', $audit->id)->where('sos', 1)->get();
    }
}
