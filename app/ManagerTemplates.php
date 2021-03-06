<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class ManagerTemplates extends Model
{
    protected $table ="manager_templates";
    protected $fillable = ['managers_id','templates_id'];    
 
    public function tdetails(){
		return $this->belongsTo('App\Template','templates_id','id');
	}

	public static function getMappedtemplate($mapped_template){

		return DB::table('templates')
			->select('templates.id')
			->where('templates.description',$mapped_template)
			->first();		
	}
}
