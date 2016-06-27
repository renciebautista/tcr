<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CheckIn extends Model
{
    public $fillable = ['user_id', 'audit_id', 'account', 'customer_code', 'customer', 'area', 'region_code', 'region',
		'distributor_code', 'distributor', 'store_code', 'store_name', 'checkin', 
		'lat', 'long'];

	public static function search($request){
		return self::select(DB::raw('audits.description as audit_month, users.name, check_ins.store_name, count(*) as frequency'))
			->where(function($query) use ($request){
            if(!empty($request->users)){
                    $query->whereIn('user_id',$request->users);
                }
            })
            ->where(function($query) use ($request){
            if(!empty($request->audits)){
                    $query->whereIn('audit_id',$request->audits);
                }
            })
            ->join('users', 'users.id','=', 'check_ins.user_id')
            ->join('audits', 'audits.id','=', 'check_ins.audit_id')
            ->groupBy('user_id')
            ->groupBy('store_code')
            ->groupBy('audit_id')
            ->orderBy('audit_id', 'users.name', 'check_ins.store_name')
            ->get();
	}
}
