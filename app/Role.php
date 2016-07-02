<?php

namespace App;

use Zizaco\Entrust\EntrustRole;
use DB;
class Role extends EntrustRole
{
    public static function myroleid($id){
    	return DB::table('role_user')
    				->select('role_id')
    				->where('user_id',$id)
    				->first();
    }

    public static function myrole($myroleid){
    	return DB::table('roles')
    				->select('roles.*')
    				->where('id',$myroleid)
    				->first();
    }
}