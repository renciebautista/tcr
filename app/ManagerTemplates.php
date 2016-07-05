<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagerTemplates extends Model
{
    protected $table ="manager_templates";
    protected $fillable = ['managers_id','templates_id'];    
    public function tdetails(){
		return $this->belongsTo('App\Template','templates_id','id');
	}
}
