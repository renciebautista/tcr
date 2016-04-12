<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AuditTemplateGroup;

class FormGroup extends Model
{
    protected $fillable = ['audit_id','audit_template_id', 'group_desc'];
    public $timestamps = false;

    public static function getTemplateGroup($id,$audit_id, $perfect_store = null){
    	$data = AuditTemplateGroup::select('form_group_id')
            ->where('audit_template_id',$id)
            ->groupBy('form_group_id')
            ->get();

        $ids = [];
        foreach ($data as $value) {
            $ids[] = $value->form_group_id;
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
