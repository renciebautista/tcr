<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AuditTemplateCategory;

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

    public static function getTemplateCategory($id,$audit_id,$perfect_store = null){
        $data = AuditTemplateCategory::select('form_category_id')
            ->where('audit_template_id',$id)
            ->groupBy('form_category_id')
            ->get();

        $ids = [];
        foreach ($data as $value) {
            $ids[] = $value->form_category_id;
        }

        return self::where('audit_id',$audit_id)
            ->whereIn('id',$ids)
            ->where(function($query) use ($perfect_store){
            if(!empty($perfect_store )){
                    $query->where('perfect_store',1);
                }
            })
            ->get();
    }
}   
