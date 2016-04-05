<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PostedAudit extends Model
{
    public function user(){
    	return $this->belongsTo('App\User','user_id');
    }

    public function audit(){
    	return $this->belongsTo('App\Audit','audit_id');
    }

    public function isPerfectStore(){
    	if($this->perfect_store == 1){
    		return "YES";
    	}else{
    		return "NO";
    	}
    	
    }

    public static function getUserSummary(){
        $query = sprintf('
            select table_visited.user_id, table_visited.audit_id, users.name as user_name,audits.description as audit_description, mapped_stores,store_visited, (mapped_stores - store_visited) as to_visited, coalesce(perfect_stores,0) as perfect_stores
            from (
                select user_id,audit_id,count(*) as store_visited from `posted_audits`
                group by user_id, audit_id
            ) as table_visited
            join (
                select user_id, audit_id, count(*) as mapped_stores from audit_stores
                group by user_id, audit_id 
            )as mapped_stores on mapped_stores.user_id = table_visited.user_id and mapped_stores.audit_id = table_visited.audit_id
            join users on users.id = table_visited.user_id
            join audits on audits.id = table_visited.audit_id
            left join(
                select user_id,audit_id,count(*) as perfect_stores from `posted_audits`
                where perfect_store = 1
                group by user_id, audit_id
            ) as perfect_stores on perfect_stores.user_id = table_visited.user_id and perfect_stores.audit_id = table_visited.audit_id
            order by audits.description, users.name');
        return DB::select(DB::raw($query));
    }

    public static function getUserSummaryDetails($audit_id,$user_id){
        $query = sprintf('
            select table_visited.user_id, table_visited.audit_id, users.name as user_name,audits.description as audit_description, mapped_stores,store_visited, (mapped_stores - store_visited) as to_visited, coalesce(perfect_stores,0) as perfect_stores
            from (
                select user_id,audit_id,count(*) as store_visited from `posted_audits`
                group by user_id, audit_id
            ) as table_visited
            join (
                select user_id, audit_id, count(*) as mapped_stores from audit_stores
                group by user_id, audit_id 
            )as mapped_stores on mapped_stores.user_id = table_visited.user_id and mapped_stores.audit_id = table_visited.audit_id
            join users on users.id = table_visited.user_id
            join audits on audits.id = table_visited.audit_id
            left join(
                select user_id,audit_id,count(*) as perfect_stores from `posted_audits`
                where perfect_store = 1
                group by user_id, audit_id
            ) as perfect_stores on perfect_stores.user_id = table_visited.user_id and perfect_stores.audit_id = table_visited.audit_id
            where table_visited.user_id = %d 
            and table_visited.audit_id = %d
            order by audits.description, users.name',$user_id,$audit_id );
        return DB::select(DB::raw($query))[0];
    }

    public static function getStores($audit_id,$user_id){
        return self::where('audit_id',$audit_id)
            ->where('user_id',$user_id)
            ->get();
        ;
    }
}
