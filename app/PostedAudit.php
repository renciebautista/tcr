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
            ->orderBy('audits.id')
            ->get();
    }

    public static function getRegions(){
        return self::select('region_code', 'region')
            ->groupBy('region_code')
            ->orderBy('region')
            ->get();
    }

    public static function getTemplates(){
        return self::select('channel_code', 'template')
            ->groupBy('channel_code')
            ->orderBy('template')
            ->get();
    }

    public static function getPostedStores(){
        return self::select('store_code', 'store_name')
            ->groupBy('store_code')
            ->orderBy('store_name')
            ->get();
    }

   

    public static function search($request){
        $data = self::select(DB::raw('posted_audits.*, audit_stores.pjp, audit_stores.freq'))
            ->leftJoin('audit_stores', function($join){
                $join->on('audit_stores.audit_id', '=', 'posted_audits.audit_id');
                $join->on('audit_stores.store_code','=','posted_audits.store_code');
            })
            ->where(function($query) use ($request){
            if(!empty($request->users)){
                    $query->whereIn('posted_audits.user_id',$request->users);
                }
            })
            ->where(function($query) use ($request){
            if(!empty($request->stores)){
                    $query->whereIn('posted_audits.store_code',$request->stores);
                }
            })
            ->where(function($query) use ($request){
            if(!empty($request->audits)){
                    $query->whereIn('posted_audits.audit_id',$request->audits);
                }
            })
            
            ->orderBy('posted_audits.updated_at','desc')
            ->get();

        foreach ($data as $key => $value) {

            $perfect_store = PostedAuditCategorySummary::getPerfectCategory($value);
            $data[$key]->perfect_category =  $perfect_store['perfect_count'];
            $data[$key]->total_category =  $perfect_store['total'];
            if($perfect_store['perfect_count'] == 0){
                 $data[$key]->perfect_percentage =  0.00 ;
            }else{
                 $data[$key]->perfect_percentage =  number_format(($perfect_store['perfect_count'] / $perfect_store['total'] ) * 100,2) ;
            }
           
        }
        return $data;
    }

    public static function customerSearch($data,$request = null){
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

            ->where('customer_code',$data['customer_code'])
            ->where('region_code',$data['region_code'])
            ->where('channel_code',$data['channel_code'])
            ->where('audit_id',$data['audit_id'])
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
        $pjps = '';
        if(!empty($request->users)){
            $users = "and users.id in (". implode(',', $request->get('users')) .')';
        }
        if(!empty($request->audits)){
            $audits = "and posted_audits.audit_id in (". implode(',', $request->get('audits')) .')';
        }
        if(!empty($request->pjps)){
            if(count($request->pjps) == 1){
                if($request->pjps[0] == 1){
                    $pjps = "where audit_stores.pjp = 1";
                }else{
                    $pjps = "where audit_stores.pjp = 0";
                }
            }
        }

        $query = sprintf('
            select users.id as user_id, posted_audits.audit_id,users.name, audits.description,
            COALESCE(tbl_mapped.mapped_stores,0) as mapped_stores, tbl_posted.store_visited
            from posted_audits
            inner join users on users.id = posted_audits.user_id
            inner join audits on audits.id = posted_audits.audit_id
            left join (
                select user_id, audit_id, count(*) as mapped_stores from audit_stores
                %s
                group by user_id, audit_id
            ) as tbl_mapped using(user_id,audit_id)
            left join (
                select posted_audits.user_id,posted_audits.audit_id,count(*) as store_visited 
                from `posted_audits`
                left join audit_stores on (audit_stores.audit_id = posted_audits.audit_id  and audit_stores.store_code = posted_audits.store_code)
                %s
                group by posted_audits.user_id, posted_audits.audit_id
            ) as tbl_posted using(user_id,audit_id)
            where tbl_posted.store_visited > 0
            %s %s
            group by posted_audits.user_id, posted_audits.audit_id
            order by audits.description, users.name',$pjps ,$pjps, $users,$audits);

        $data = DB::select(DB::raw($query));

        foreach ($data as $key => $value) {
            $audit = Audit::findOrFail($value->audit_id);
            $user = User::findOrFail($value->user_id);
            $summary = UserSummary::getSummary($audit,$user);

            $data[$key]->perfect_store = $summary->detail->perfect_store_count;
            $pjp_target = AuditUserPjp::where('user_id', $value->user_id)
                ->where('audit_id', $value->audit_id)
                ->first();
            if(!empty($pjp_target)){
                $data[$key]->target = $pjp_target->target;
            }
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

    public static function getStoresByUser($audit_id,$user_id){
        $data = self::where('audit_id',$audit_id)
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

    public static function getCustomerStores($audit_id,$channel_code,$region_code,$customer_code){
        $data = self::where('audit_id',$audit_id)
            ->where('channel_code',$channel_code)
            ->where('region_code',$region_code)
            ->where('customer_code',$customer_code)
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

    public static function getCustomerSummary($request = null){
        $customers = '';
        $regions = '';
        $templates = '';
        $audits = '';
        $pjps = '';
        if(!empty($request->customers)){
            $customers = "and customer_code in ('". implode("','", $request->customers) ."')";
        }
        if(!empty($request->regions)){
            $regions = "and region_code in ('". implode("','", $request->regions) ."')";
        }
        if(!empty($request->templates)){
            $templates = "and channel_code in ('". implode("','", $request->templates) ."')";
        }
        if(!empty($request->audits)){
            $audits = "and audit_id in ('". implode("','", $request->audits) ."')";
        }

        if(!empty($request->pjps)){
            if(count($request->pjps) == 1){
                if($request->pjps[0] == 1){
                    $pjps = "and pjp = 1";
                }else{
                    $pjps = "and pjp = 0";
                }
                
            }
            
        }

        // dd($pjps);

        $query = sprintf('select customer_code,customer,region_code, region,channel_code,audit_id,audit_tempalte,
            audits.description as audit_group,
            mapped_stores, count(*) as visited_stores,
            (sum(osa) / count(*))  as osa_ave,
            (sum(npi) / count(*)) as npi_ave,
            (sum(planogram) / count(*)) as planogram_ave
            from posted_audits
            inner join audits on audits.id = posted_audits.audit_id
            left join (
                select audit_id, channel_code, template as audit_tempalte, count(*) as mapped_stores from
                audit_stores
                group by audit_id, channel_code
            ) as tbl_mapped using(channel_code,audit_id)
            where mapped_stores > 0
            %s
            %s
            %s
            %s
            group by audit_id, channel_code, region_code',$customers,$regions,$templates,$audits);

        $data = DB::select(DB::raw($query));

        // dd($data);

        foreach ($data as $key => $value) {
            $stores = self::getCustomerStores($value->audit_id,$value->channel_code,$value->region_code,$value->customer_code);
            $perfect_stores = 0;
            foreach ($stores as $store) {
                if($store->perfect_percentage == '100.00'){
                    $perfect_stores++;
                }
            }
            $data[$key]->perfect_stores = $perfect_stores;
        }

        return $data;

    }

    public static function getCustomer($customer_code, $audit_id){
        return self::where('customer_code', $customer_code)
            ->where('audit_id', $audit_id)
            ->groupBy('customer_code')
            ->first();
    }

    public static function getRegion($region_code, $audit_id){
        return self::where('region_code', $region_code)
            ->where('audit_id', $audit_id)
            ->groupBy('region_code')
            ->first();
    }

    public static function getTemplate($channel_code, $audit_id){
        return self::where('channel_code', $channel_code)
            ->where('audit_id', $audit_id)
            ->groupBy('channel_code')
            ->first();
    }

    public static function getOsaSku($request){
        $audits = '';
        if(!empty($request->audits)){
            $audits = "and posted_audits.audit_id in (". implode(',', $request->get('audits')) .')';
        }

        $templates = '';
        if(!empty($request->templates)){
            $templates = "and posted_audits.channel_code in ('". implode("','", $request->get('templates')) ."')";
        }

        $customers = '';
        if(!empty($request->customers)){
            $customers = "and posted_audits.customer_code in ('". implode("','", $request->get('customers')) ."')";
        }

        $categories = '';
        if(!empty($request->categories)){
            $categories = "and posted_audit_details.category in ('". implode("','", $request->get('categories')) ."')";
        }


        $osas = '';
        $osa_desc = [];
        $formgroups = FormGroup::where('osa',1)->get();
        foreach ($formgroups as $group) {
            $osa_desc[] = $group->group_desc;
        }

        if(!empty($osa_desc)){
            $osas = "and posted_audit_details.group in ('". implode("','", $osa_desc)  ."')";
        }
        
        $query = sprintf('
            select tbl_stores.audit_id, description, posted_audits.channel_code, posted_audits.template, 
            category, posted_audit_details.group, posted_audit_details.prompt, store_count,  
            count(posted_audit_details.prompt)  as availability,
            (count(posted_audit_details.prompt) / store_count) * 100 as osa_percent,
            posted_audits.customer
            from posted_audit_details
            join posted_audits on posted_audits.id = posted_audit_details.posted_audit_id
            join audits on audits.id = posted_audits.audit_id
            join(
                select audit_id,channel_code, count(*) as store_count from posted_audits
                group by audit_id,channel_code
            ) as tbl_stores on (tbl_stores.audit_id = posted_audits.audit_id and tbl_stores.channel_code = posted_audits.channel_code)
            where posted_audit_details.type = "CONDITIONAL"
            and posted_audit_details.answer = "AVAILABLE ON SHELF"
            %s
            %s
            %s
            %s
            %s
            group by prompt,posted_audits.channel_code
            order by osa_percent, audit_id, template',$osas,$audits,$templates, $customers, $categories);

        return DB::select(DB::raw($query));
    }

    public static function getNpiSku($request){
        $audits = '';
        if(!empty($request->audits)){
            $audits = "and posted_audits.audit_id in (". implode(',', $request->get('audits')) .')';
        }

        $templates = '';
        if(!empty($request->templates)){
            $templates = "and posted_audits.channel_code in ('". implode("','", $request->get('templates')) ."')";
        }

        $customers = '';
        if(!empty($request->customers)){
            $customers = "and posted_audits.customer_code in ('". implode("','", $request->get('customers')) ."')";
        }

        $categories = '';
        if(!empty($request->categories)){
            $categories = "and posted_audit_details.category in ('". implode("','", $request->get('categories')) ."')";
        }

        $npis = '';
        $npi_desc = [];
        $formgroups = FormGroup::where('npi',1)->get();
        foreach ($formgroups as $group) {
            $npi_desc[] = $group->group_desc;
        }

        if(!empty($npi_desc)){
            $npis = "and posted_audit_details.group in ('". implode("','", $npi_desc)  ."')";
        }
        
        $query = sprintf('
            select tbl_stores.audit_id, description, posted_audits.channel_code, posted_audits.template, 
            category, posted_audit_details.group, posted_audit_details.prompt, store_count,  
            count(posted_audit_details.prompt)  as availability,
            (count(posted_audit_details.prompt) / store_count) * 100 as osa_percent,
            posted_audits.customer
            from posted_audit_details
            join posted_audits on posted_audits.id = posted_audit_details.posted_audit_id
            join audits on audits.id = posted_audits.audit_id
            join(
                select audit_id,channel_code, count(*) as store_count from posted_audits
                group by audit_id,channel_code
            ) as tbl_stores on (tbl_stores.audit_id = posted_audits.audit_id and tbl_stores.channel_code = posted_audits.channel_code)
            where posted_audit_details.type = "CONDITIONAL"
            and posted_audit_details.answer = "AVAILABLE ON SHELF"
            %s
            %s
            %s
            %s
            %s
            group by prompt,posted_audits.channel_code
            order by osa_percent, audit_id, template',$npis,$audits,$templates, $categories, $customers);

        return DB::select(DB::raw($query));
    }

    public static function getCustomizedPlanoSku($request){
        $audits = '';
        if(!empty($request->audits)){
            $audits = "and posted_audits.audit_id in (". implode(',', $request->get('audits')) .')';
        }

        $templates = '';
        if(!empty($request->templates)){
            $templates = "and posted_audits.channel_code in ('". implode("','", $request->get('templates')) ."')";
        }

        $customers = '';
        if(!empty($request->customers)){
            $customers = "and posted_audits.customer_code in ('". implode("','", $request->get('customers')) ."')";
        }

        $categories = '';
        if(!empty($request->categories)){
            $categories = "and posted_audit_details.category in ('". implode("','", $request->get('categories')) ."')";
        }

        $planos = '';
        $plano_desc = [];
        $formgroups = FormGroup::where('plano',1)->get();
        foreach ($formgroups as $group) {
            $plano_desc[] = $group->group_desc;
        }

        if(!empty($plano_desc)){
            $planos = "and posted_audit_details.group in ('". implode("','", $plano_desc)  ."')";
        }

        // dd($plano_desc);
        
        $query = sprintf('
            select tbl_stores.audit_id, description, posted_audits.channel_code, posted_audits.template, 
            category, posted_audit_details.group, posted_audit_details.prompt, store_count,  
            count(posted_audit_details.prompt)  as availability,
            (count(posted_audit_details.prompt) / store_count) * 100 as osa_percent,
            posted_audits.customer
            from posted_audit_details
            join posted_audits on posted_audits.id = posted_audit_details.posted_audit_id
            join audits on audits.id = posted_audits.audit_id
            join(
                select audit_id,channel_code, count(*) as store_count from posted_audits
                group by audit_id,channel_code
            ) as tbl_stores on (tbl_stores.audit_id = posted_audits.audit_id and tbl_stores.channel_code = posted_audits.channel_code)
            where posted_audit_details.type = "CONDITIONAL"
            and posted_audit_details.answer = "IMPLEMENTED"
            %s
            %s
            %s
            %s
            %s
            group by prompt,posted_audits.channel_code
            order by osa_percent, audit_id, template',$planos,$audits,$templates, $categories, $customers);

        return DB::select(DB::raw($query));
    }

    public static function getSos($request = null){
        // dd($request->all());
        $audits = '';
        if(!empty($request->audits)){
            $audits = "and posted_audits.audit_id in ('". implode("','", $request->get('audits')) ."')";
        }

        $customers = '';
        if(!empty($request->customers)){
            $customers = "and posted_audits.customer_code in ('". implode("','", $request->get('customers')) ."')";
        }

        // dd($customers);

        $templates = '';
        if(!empty($request->templates)){
            $templates = "and posted_audits.channel_code in ('". implode("','", $request->get('templates')) ."')";
        }

        $stores = '';
        if(!empty($request->stores)){
            $stores = "and posted_audits.store_code in ('". implode("','", $request->get('stores')) ."')";
        }

        $categories = '';
        if(!empty($request->categories)){
            $categories = "and posted_audit_details.category in ('". implode("','", $request->get('categories')) ."')";
        }

        $users = '';
        if(!empty($request->users)){
            $users = "and posted_audits.user_id in ('". implode("','", $request->get('users')) ."')";
        }

        $soss = '';
        $sos_desc = [];
        $formgroups = FormGroup::where('sos',1)->get();
        foreach ($formgroups as $group) {
            $sos_desc[] = $group->group_desc;
        }

        if(!empty($soss)){
            $soss = "and posted_audit_details.group in ('". implode("','", $sos_desc)  ."')";
        }

        $query = sprintf("
            select audit_id, audits.description, store_name, store_code, category, answer as sos_measurement,
            posted_audits.customer, template, users.name
            from posted_audit_details
            join posted_audits on posted_audits.id = posted_audit_details.posted_audit_id
            join audits on audits.id = posted_audits.audit_id
            join users on users.id = posted_audits.user_id
            where posted_audit_details.prompt = 'PERFECT STORE -  ULP SOS PERCENTAGE'
            %s
            %s
            %s
            %s
            %s
            %s
            %s
            order by audit_id, store_name, category", $soss,$audits, $customers, $templates, $stores, $categories, $users);

        // dd($query);

        $sos_lists = DB::select(DB::raw($query));
        // dd($sos_lists);
        foreach ($sos_lists as $key => $value) {
            $store = AuditStore::where('store_code',$value->store_code)
                ->where('audit_id', $value->audit_id)
                ->first();
            $category = FormCategory::where('audit_id', $value->audit_id)
                ->where('category', $value->category)
                ->first();
            $result;
            if((!empty($store)) && (!empty($category))){
                $result = DB::select(DB::raw("select audit_store_sos.audit_store_id, 
                    audit_store_sos.form_category_id,audit_sos_lookup_details.sos_type_id,
                    audit_sos_lookup_details.less,audit_sos_lookup_details.value,audit_sos_lookup_details.audit_sos_lookup_id
                    from audit_store_sos
                    join audit_sos_lookup_details using(audit_sos_lookup_id, form_category_id, sos_type_id) 
                    where audit_store_sos.audit_store_id = :store_id
                    and audit_store_sos.form_category_id = :category_id"),array(
                   'store_id' => $store->id, 'category_id' => $category->id));
            }

            
            // dd($result[0]->value);
            if(!empty($result)){
                $sos_lists[$key]->target = $result[0]->value *100;
            }else{
                $sos_lists[$key]->target = 0;
            }

            
        }
        return $sos_lists;
    }
}
