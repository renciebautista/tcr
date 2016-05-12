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

    public static function getCustomers(){
        return self::select('customer_code', 'customer')
            ->groupBy('customer')
            ->orderBy('customer')
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
        $data = self::where(function($query) use ($request){
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
            ->orderBy('updated_at','desc')
            ->get();

        foreach ($data as $key => $value) {

            $perfect_store = PostedAuditCategorySummary::getPerfectCategory($value);
            $data[$key]->perfect_category =  $perfect_store['perfect_count'];
            $data[$key]->total_category =  $perfect_store['total'];
            $data[$key]->perfect_percentage =  number_format(($perfect_store['perfect_count'] / $perfect_store['total'] ) * 100,2) ;
        }
        return $data;
    }

    public static function getUserSummary($request = null){
        $users = '';
        $audits = '';
        if(!empty($request->users)){
            $users = "and users.id in (". implode(',', $request->get('users')) .')';
        }
        if(!empty($request->audits)){
            $audits = "and posted_audits.audit_id in (". implode(',', $request->get('audits')) .')';
        }
        $query = sprintf('
            select users.id as user_id, posted_audits.audit_id,users.name, audits.description,
            tbl_mapped.mapped_stores, tbl_posted.store_visited
            from posted_audits
            inner join users on users.id = posted_audits.user_id
            inner join audits on audits.id = posted_audits.audit_id
            left join (
                select user_id, audit_id, count(*) as mapped_stores from audit_stores
                group by user_id, audit_id
            ) as tbl_mapped using(user_id,audit_id)
            left join (
                select user_id,audit_id,count(*) as store_visited from `posted_audits`
                group by user_id, audit_id
            ) as tbl_posted using(user_id,audit_id)
            where tbl_posted.store_visited > 0
            %s %s
            group by posted_audits.user_id, posted_audits.audit_id
            order by audits.description, users.name',$users,$audits);

        $data = DB::select(DB::raw($query));

        foreach ($data as $key => $value) {
            $audit = Audit::findOrFail($value->audit_id);
            $user = User::findOrFail($value->user_id);
            $summary = UserSummary::getSummary($audit,$user);

            $data[$key]->perfect_store = $summary->detail->perfect_store_count;
        }

        return $data;
    }

    public static function getUserSummaryDetails($audit_id,$user_id){
        $query = sprintf('
            select users.id as user_id, posted_audits.audit_id,users.name, audits.description,
            tbl_mapped.mapped_stores, tbl_posted.store_visited
            from posted_audits
            inner join users on users.id = posted_audits.user_id
            inner join audits on audits.id = posted_audits.audit_id
            left join (
                select user_id, audit_id, count(*) as mapped_stores from audit_stores
                group by user_id, audit_id
            ) as tbl_mapped using(user_id,audit_id)
            left join (
                select user_id,audit_id,count(*) as store_visited from `posted_audits`
                group by user_id, audit_id
            ) as tbl_posted using(user_id,audit_id)
            where posted_audits.user_id = %d 
            and posted_audits.audit_id = %d
            order by audits.description, users.name',$user_id,$audit_id );
        return DB::select(DB::raw($query))[0];
    }

    public static function getStores($audit_id,$user_id){
        $data = self::where('audit_id',$audit_id)
            // ->join('audits', 'audits.id', '=', 'posted_audits.audit_id')
            ->where('user_id',$user_id)
            ->get();
        foreach ($data as $key => $value) {
            $perfect_store = PostedAuditCategorySummary::getPerfectCategory($value);
            $data[$key]->audit_name = $value->audit->description;
            $data[$key]->perfect_category =  $perfect_store['perfect_count'];
            $data[$key]->total_category =  $perfect_store['total'];
            $data[$key]->perfect_percentage =  $perfect_store['perfect_percentage'];
        }
        return $data;
    }
}
