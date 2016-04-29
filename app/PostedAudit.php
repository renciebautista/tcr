<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\PostedAuditCategorySummary;

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

    public static function getUsers(){
        return self::select('user_id', 'users.name')
            ->join('users','users.id', '=', 'posted_audits.user_id')
            ->groupBy('user_id')
            ->orderBy('users.name')
            ->get();
    }

    public static function getAudits(){
        return self::select('audit_id', 'audits.description')
            ->join('audits','audits.id', '=', 'posted_audits.audit_id')
            ->groupBy('audit_id')
            ->orderBy('audits.description')
            ->get();
    }

    public static function getPostedStores(){
        return self::select('store_code', 'store_name')
            ->groupBy('store_code')
            ->orderBy('store_name')
            ->get();
    }

    public static function search($request){
        return self::where(function($query) use ($request){
            if(!empty($request->users)){
                    $query->whereIn('user_id',$request->users);
                }
            })
            ->where(function($query) use ($request){
            if(!empty($request->audits)){
                    $query->whereIn('audit_id',$request->audits);
                }
            })
            ->where(function($query) use ($request){
            if(!empty($request->stores)){
                    $query->whereIn('store_code',$request->stores);
                }
            })
            ->where(function($query) use ($request){
            if(!empty($request->status)){
                    $query->whereIn('perfect_store',$request->status);
                }
            })
            ->get();
    }

    public static function getUserSummary($request = null){
        $users = '';
        $audits = '';
        if(!empty($request->users)){
            $users = "and table_visited.user_id in (". implode(',', $request->get('users')) .')';
        }
        if(!empty($request->audits)){
            $users = "and table_visited.audit_id in (". implode(',', $request->get('audits')) .')';
        }
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
            where mapped_stores > 0
            %s %s
            order by audits.description, users.name ',$users,$audits);
        return DB::select(DB::raw($query));
    }

    public static function getUserSummaryDetails($audit_id,$user_id){
        $query = sprintf('
            select table_visited.user_id, table_visited.audit_id, users.name as user_name,audits.description as audit_description, mapped_stores,store_visited,
             (mapped_stores - store_visited) as to_visited, coalesce(perfect_stores,0) as perfect_stores,
             ((perfect_stores / store_visited) * 100) as perfect_stores_achievement
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
        $data = self::where('audit_id',$audit_id)
            ->where('user_id',$user_id)
            ->get();

        foreach ($data as $key => $value) {
            $perfect_store = PostedAuditCategorySummary::getPerfectCategory($value);
            $data[$key]->perfect_category =  $perfect_store['perfect_count'];
            $data[$key]->total_category =  $perfect_store['total'];
            $data[$key]->perfect_percentage =  number_format(($perfect_store['perfect_count'] / $perfect_store['total'] ) * 100,2) ;
        }
        return $data;
    }
}
