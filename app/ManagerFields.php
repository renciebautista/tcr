<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;
class ManagerFields extends Model
{
	protected $table ="manager_fields";
    protected $fillable = ['managers_id','fields_id'];

    public function fdetails(){
		return $this->belongsTo('App\user','fields_id','id');
	}
	public function roles()
    {
        return $this->belongsToMany('App\Role','role_user');
    }
    public function role_name()
    {
        return $this->roles[0]->name;
    }    
}
