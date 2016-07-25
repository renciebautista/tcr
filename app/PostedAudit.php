<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\PostedAuditCategorySummary;
use Auth;
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

    public static function getUsers($auth_user){        
        
        $role = DB::table('role_user')
            ->select('role_id')
            ->where('user_id',$auth_user)
            ->first();
        if($role->role_id === 4){
            // fields na naka map sa user
            $myFields = DB::table('manager_fields')
                ->select('manager_fields.*')
                ->where('manager_fields.managers_id','=',$auth_user)
                ->get();
            $data = [];
            foreach ($myFields as $value) {
                $data[] =  $value->fields_id;
            } 
      
            return self::select('user_id', 'users.name')
                ->whereIn('user_id',$data)            
                ->join('users','users.id', '=', 'posted_audits.user_id')                    
                ->groupBy('user_id')
                ->orderBy('users.name')
                ->get();
        }
        else{
            return self::select('user_id', 'users.name')    
                ->join('users','users.id', '=', 'posted_audits.user_id')                    
                ->groupBy('user_id')
                ->orderBy('users.name')
                ->get();
        }
    }
    public static function getUserAF($auth_user,$template,$customer){ 
        
        $role = Role::myroleid($auth_user); 

        if(!empty($template) AND !empty($customer)){

            $temp = [];        

            foreach($template as $t){
                $temp[] = $t;
            }

            $templates = DB::table('templates')
                ->whereIn('templates.code',$temp)
                ->get();

            $tmp = [];

            $customer_code = [];

            foreach($customer as $c){
                $customer_code[] = $c;
            }

            foreach($templates as $tp){
                $tmp [] =$tp->description;
            }

            return self::select('user_id', 'users.name')   
                ->whereIn('customer_code',$customer_code)
                ->whereIn('posted_audits.template',$tmp)
                ->join('users','users.id', '=', 'posted_audits.user_id')                    
                ->groupBy('user_id')
                ->orderBy('users.name')
                ->get();        
        }
        if(!empty($customer)){
            $customer_code = [];
            foreach($customer as $c){
                $customer_code[] = $c;
            }            
            return self::select('user_id', 'users.name')   
                ->whereIn('customer_code',$customer_code)
                ->join('users','users.id', '=', 'posted_audits.user_id')                    
                ->groupBy('user_id')
                ->orderBy('users.name')
                ->get();        
        }
        if(empty($customer)){      

            if($role->role_id === 3){

                $myTemplates = DB::table('manager_templates')
                ->join('templates', 'templates.id', '=', 'manager_templates.templates_id')
                ->where('manager_templates.managers_id','=',$auth_user)
                ->get();    

                $tdes =[];
                
                foreach($myTemplates as $td){ 
                    $tdes[]=$td->description;            
                }
                
                return self::select('user_id', 'users.name','customer_code')
                    ->whereIn('template',$tdes)                
                    ->join('users','users.id', '=', 'posted_audits.user_id')                    
                    ->groupBy('user_id')
                    ->orderBy('users.name')
                    ->get();              
            }      
            if($role->role_id === 1 || $role->role_id === 2){

            return self::select('user_id', 'users.name')                   
                ->join('users','users.id', '=', 'posted_audits.user_id')                    
                ->groupBy('user_id')
                ->orderBy('users.name')
                ->get();        
            }
        }
    }
       
    
    public static function getCustomers($use){
        $auth_user = Auth::id();
        $role = DB::table('role_user')
            ->select('role_id')
            ->where('user_id',$auth_user)
            ->first();
        if($role->role_id === 4){
            $users=[];
            foreach($use as $u) {
                $users[]=$u->user_id;
            }        
            return self::select('customer_code', 'customer')
                ->whereIn('user_id',$users)
                ->groupBy('customer')
                ->orderBy('customer')
                ->get();
        }
        else{
            return self::select('customer_code', 'customer')                
                ->groupBy('customer')
                ->orderBy('customer')
                ->get();
        }
        
    }
    public static function getCustomersMT($temp){        
        $templates=[];

        foreach($temp as $t) {
            $templates[]=$t->template;
        }        
        return self::select('customer_code', 'customer')
            ->whereIn('template',$templates)
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

    public static function getRegions($use){
        $user = [];
        foreach($use as $u){
            $user[]=$u->user_id;
        }
        return self::select('region_code', 'region')
            ->whereIn('user_id',$user)
            ->groupBy('region_code')
            ->orderBy('region')
            ->get();
    }

    public static function getRegionsfilter($use,$cus){
        $user = [];
        foreach($use as $u){
            $user[]=$u->user_id;
        }
        $custom = [];
        
        foreach($cus as $c){
            $custom[]=$c;
        }                
        return self::select('region_code', 'region')
            ->whereIn('user_id',$user)
            ->whereIn('customer_code',$custom)
            ->groupBy('region_code')
            ->orderBy('region')
            ->get();
    }

    public static function getTemplates($use){
        $auth_user = Auth::id();
        $role = DB::table('role_user')
            ->select('role_id')
            ->where('user_id',$auth_user)
            ->first(); 
      

        if($role->role_id === 4){
              
            $users = [];
                foreach($use as $u){
                    $users[]=$u->user_id;
                }
            // get user templates
            $anotherTemplates = DB::table('posted_audits')
                ->select('posted_audits.*')
                ->whereIn('posted_audits.user_id',$users)
                ->get();        
            $tagged = [];
                foreach($anotherTemplates as $at){
                    $tagged[] =  $at->template;
                } 

                return self::select('channel_code', 'template')
                    ->whereIn('template',$tagged)
                    ->groupBy('channel_code')
                    ->orderBy('template')
                    ->get();
        }
        else{
                return self::select('channel_code', 'template')                    
                    ->groupBy('channel_code')
                    ->orderBy('template')
                    ->get();
        }
    }    

        //-------------------------------->OLD FILTERING<--------------------------------------//
        //get user templates
        // $myTemplates = DB::table('manager_templates')
        //     ->select('manager_templates.*')
        //     ->where('manager_templates.managers_id','=',$auth_user)
        //     ->get();

        // $data = [];

        // foreach ($myTemplates as $value) {
        //     $data[] =  $value->templates_id;
        // }
        // //owa
        // //get user fieldss        
        // $myFields = DB::table('manager_fields')
        //     ->select('manager_fields.*')
        //     ->where('manager_fields.managers_id','=',$auth_user)
        //     ->get();
        // $datas = [];
        // foreach ($myFields as $value) {
        //     $datas[] =  $value->fields_id;
        // }       

        // $anotherTemplates = DB::table('posted_audits')
        //     ->select('posted_audits.*')
        //     ->whereIn('posted_audits.user_id',$datas)
        //     ->get();        
        // $tagged = [];
        //     foreach($anotherTemplates as $at){
        //         $tagged[] =  $at->template;
        //     }                
        // $temp = DB::table('templates')
        //     ->select('templates.*')
        //     ->whereIn('id',$data)
        //     ->get();        
        // foreach ($temp as $value) {
        //     $tagged[] =  $value->description;
        // }        

        // return self::select('channel_code', 'template')
        //     ->whereIn('template',$tagged)
        //     ->groupBy('channel_code')
        //     ->orderBy('template')
        //     ->get();
        //-------------------------------->END OF OLD FILTERING<--------------------------------------//    
    public static function getTemplatesMT($auth_user){
        $auth_user = Auth::id();
        $role = DB::table('role_user')
            ->select('role_id')
            ->where('user_id',$auth_user)
            ->first();     

        if($role->role_id === 3){
           
            $tempTag = DB::table('manager_templates') 
                    ->select('manager_templates.*')
                    ->where('manager_templates.managers_id',$auth_user)
                    ->get();            
            $tTaf = [];
            foreach($tempTag as $ttaf){
                $tTaf[]=$ttaf->templates_id;
            }

            $template_field = DB::table('templates')
                ->select('templates.description')
                ->whereIn('id',$tTaf)
                ->get();
            
            $tagged = [];
                foreach($template_field as $at){
                    $tagged[] =  $at->description;
                } 
                return self::select('channel_code', 'template')
                    ->whereIn('template',$tagged)
                    ->groupBy('channel_code')
                    ->orderBy('template')
                    ->get();
        }
    }
    public static function getUsersMT($temp){
        $auth_user = Auth::id();
        $role = DB::table('role_user')
            ->select('role_id')
            ->where('user_id',$auth_user)
            ->first(); 
        
        $templates = [];
            foreach($temp as $t){
                $templates[]=$t->template;
            }

        if($role->role_id === 3){
            // get user templates
        $usersMT = DB::table('posted_audits')
            ->select('posted_audits.*')
            ->whereIn('posted_audits.template',$templates)
            ->get();               
        $tagged = [];
            foreach($usersMT as $umt){
                $tagged[] =  $umt->user_id;
            } 
            return self::select('user_id', 'users.name')
                ->whereIn('user_id',$tagged)            
                ->join('users','users.id', '=', 'posted_audits.user_id')                    
                ->groupBy('user_id')
                ->orderBy('users.name')
                ->get();
        }
    }

    public static function getPostedStores($use){
        $auth_user = Auth::id();
        $role = DB::table('role_user')
            ->select('role_id')
            ->where('user_id',$auth_user)
            ->first(); 
        if($role->role_id === 4)
        {
            $users=[];
            foreach($use as $u) {
                $users[]=$u->user_id;
            }       
            return self::select('store_code', 'store_name','user_id')
            ->whereIn('user_id',$users)
            ->groupBy('store_code')
            ->orderBy('store_name')
            ->get();
        }
        else{
            return self::select('store_code', 'store_name','user_id')                
                ->groupBy('store_code')
                ->orderBy('store_name')
                ->get();   
        }
        
        
    }
    public static function getPostedStoresMT($temp){
        $templates=[];
        foreach($temp as $t) {
            $templates[]=$t->template;
        }   
        return self::select('store_code', 'store_name','user_id')
            ->whereIn('template',$templates)
            ->groupBy('store_code')
            ->orderBy('store_name')
            ->get();
    }

    public static function getStoresfilter($use,$cus){
        $users=[];
        foreach($use as $u) {
            $users[] = $u->user_id;
        }          
        $cust = [];
        foreach($cus as $c){
            $cust[] = $c;
        }
        return self::select('store_code', 'store_name','user_id')
            ->whereIn('user_id',$users)
            ->whereIn('customer_code',$cust)
            ->groupBy('store_code')
            ->orderBy('store_name')
            ->get();
    }
    public static function getStoresfilterAF($customer,$template,$user){

        $auth_user = Auth::id();
        $role = Role::myroleid($auth_user); 

        if(!empty($user) && !empty($template) && !empty($customer)){

            $users=[];        

            foreach($user as $u) {
                $users[] = $u;
            }          

            $customer_code = [];

            foreach($customer as $c){
                $customer_code[] = $c;
            }

            $temp = DB::table('templates')
                ->whereIn('templates.code',$template)
                ->get();
            $templates = [];

            foreach($temp as $t){
                $templates[] = $t->description;
            }

            return self::select('store_code', 'store_name','user_id')
                ->whereIn('user_id',$users)
                ->whereIn('customer_code',$customer_code)
                ->whereIn('template',$templates)            
                ->groupBy('store_code')
                ->orderBy('store_name')
                ->get();
        }
        
        if(!empty($customer) AND !empty($template)){
            $customer_code = [];

            foreach($customer as $c){
                $customer_code[] = $c;
            }
            $temp = DB::table('templates')
                ->whereIn('templates.code',$template)
                ->get();
            $templates = [];

            foreach($temp as $t){
                $templates[] = $t->description;
            }

            return self::select('store_code', 'store_name','user_id')                
                ->whereIn('customer_code',$customer_code)
                ->whereIn('template',$templates)            
                ->groupBy('store_code')
                ->orderBy('store_name')
                ->get();
        }
        if(!empty($customer)){
            $customer_code = [];

            foreach($customer as $c){
                $customer_code[] = $c;
            }           

            return self::select('store_code', 'store_name','user_id')                
                ->whereIn('customer_code',$customer_code)                         
                ->groupBy('store_code')
                ->orderBy('store_name')
                ->get();
        }
        if(empty($customer)){

            if($role->role_id === 1 || $role->role_id === 2){

                return self::select('store_code', 'store_name','user_id')                                    
                ->groupBy('store_code')
                ->orderBy('store_name')
                ->get();    

            }
            if($role->role_id === 3){

                $myTemplates = DB::table('manager_templates')
                ->join('templates', 'templates.id', '=', 'manager_templates.templates_id')
                ->where('manager_templates.managers_id','=',$auth_user)
                ->get();    

                $tdes =[];
                
                foreach($myTemplates as $td){ 
                    $tdes[]=$td->description;            
                }

                return self::select('store_code', 'store_name','user_id')                                    
                    ->whereIn('template',$tdes)
                    ->groupBy('store_code')
                    ->orderBy('store_name')
                    ->get();

            }
            if($role->role_id === 4){

            }
        }
        
    }
     public static function getauditfiltersAF($store,$customer,$template,$user){
        if(!empty($customer) && !empty($template) && !empty($user) && !empty($store)){
            $users=[];        
            foreach($user as $u) {
                $users[] = $u;
            }          

            $customer_code = [];

            foreach($customer as $c){
                $customer_code[] = $c;
            }
           
            $temp = DB::table('templates')
                ->whereIn('templates.code',$template)
                ->get();
            $templates = [];

            foreach($temp as $t){
                $templates[] = $t->description;
            }
            
            $stores = [];

            foreach($store as $s){
                $stores[]= $s;
            }

             return self::select('audit_id', 'audits.description','posted_audits.*')
                ->join('audits','audits.id', '=', 'posted_audits.audit_id')
                ->whereIn('user_id',$users)
                ->whereIn('customer_code',$customer_code)
                ->whereIn('template',$templates)    
                ->whereIn('store_code',$stores)
                ->groupBy('audit_id')
                ->orderBy('audits.id')
                ->get();
        }
        if(!empty($customer) && !empty($template) && !empty($user)){
            $users=[];        
            foreach($user as $u) {
                $users[] = $u;
            }          

            $customer_code = [];

            foreach($customer as $c){
                $customer_code[] = $c;
            }
           
            $temp = DB::table('templates')
                ->whereIn('templates.code',$template)
                ->get();
            $templates = [];

            foreach($temp as $t){
                $templates[] = $t->description;
            }        

             return self::select('audit_id', 'audits.description','posted_audits.*')
                ->join('audits','audits.id', '=', 'posted_audits.audit_id')
                ->whereIn('user_id',$users)
                ->whereIn('customer_code',$customer_code)
                ->whereIn('template',$templates)                    
                ->groupBy('audit_id')
                ->orderBy('audits.id')
                ->get();
        }
        if(!empty($customer) && !empty($template)){

            $customer_code = [];

            foreach($customer as $c){
                $customer_code[] = $c;
            }
           
            $temp = DB::table('templates')
                ->whereIn('templates.code',$template)
                ->get();
            $templates = [];

            foreach($temp as $t){
                $templates[] = $t->description;
            }        

             return self::select('audit_id', 'audits.description','posted_audits.*')
                ->join('audits','audits.id', '=', 'posted_audits.audit_id')                
                ->whereIn('customer_code',$customer_code)
                ->whereIn('template',$templates)                    
                ->groupBy('audit_id')
                ->orderBy('audits.id')
                ->get();        
        }
        if(!empty($customer)){
            $customer_code = [];

            foreach($customer as $c){
                $customer_code[] = $c;
            }                     
            return self::select('audit_id', 'audits.description','posted_audits.*')
                ->join('audits','audits.id', '=', 'posted_audits.audit_id')                
                ->whereIn('customer_code',$customer_code)                        
                ->groupBy('audit_id')
                ->orderBy('audits.id')
                ->get();  
        }
        if(empty($customer)){
            return self::select('audit_id', 'audits.description','posted_audits.*')
                ->join('audits','audits.id', '=', 'posted_audits.audit_id')                                                
                ->groupBy('audit_id')
                ->orderBy('audits.id')
                ->get();  
        }
            
    }
    public static function getUserStoresfilter($use,$userfilt){        
        $usfil=[];
        foreach($userfilt as $u){
            $usfil[] = $u;
        }
        return self::select('store_code', 'store_name','user_id')
            ->whereIn('user_id',$usfil)            
            ->groupBy('store_code')
            ->orderBy('store_name')
            ->get();
    }      

    public static function search($request,$usse){         
        $auth_user = Auth::id();
        $role = DB::table('role_user')
            ->select('role_id')
            ->where('user_id',$auth_user)
            ->first(); 

        $users=[];
        foreach($usse as $u) {
            $users[]=$u->user_id;
        }    
        $tem = [];
        $t = DB::table('templates')
            ->select('templates.*')
            ->whereIn('templates.code',$request->templates)
            ->get();    
            foreach($t as $te){
                $tem[] = $te->description;
            }
        $request->templates = $tem;
        if($role->role_id === 4){
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
                if(!empty($request->templates)){
                        $query->whereIn('posted_audits.template',$request->templates);
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
                ->where(function($query) use ($request){
                if(!empty($request->customers)){
                        $query->whereIn('posted_audits.customer_code',$request->customers);
                    }
                })
                ->where(function($query) use ($request){
                if(!empty($request->templates)){
                        $query->whereIn('posted_audits.template',$request->templates);
                    }
                })               
                ->whereIn('posted_audits.user_id',$users)
                ->orderBy('posted_audits.updated_at','desc')
                ->get();    
            }
            else{
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
                if(!empty($request->templates)){
                        $query->whereIn('posted_audits.template',$request->templates);
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
                ->where(function($query) use ($request){
                if(!empty($request->customers)){
                        $query->whereIn('posted_audits.customer_code',$request->customers);
                    }
                })
                ->where(function($query) use ($request){
                if(!empty($request->templates)){
                        $query->whereIn('posted_audits.template',$request->templates);
                    }
                }) 
                ->orderBy('posted_audits.updated_at','desc')
                ->get();       
            }
            

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
    public static function searchMT($request,$temp){         
        $templates=[];
        foreach($temp as $t) {
            $templates[]=$t->template;
        }    
        $tem = [];
        $t = DB::table('templates')
            ->select('templates.*')
            ->whereIn('templates.code',$request->templates)
            ->get();    
            foreach($t as $te){
                $tem[] = $te->description;
            }
        $request->templates = $tem;
        
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
            if(!empty($request->templates)){
                    $query->whereIn('posted_audits.template',$request->templates);
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
            ->where(function($query) use ($request){
            if(!empty($request->customers)){
                    $query->whereIn('posted_audits.customer_code',$request->customers);
                }
            })
            ->where(function($query) use ($request){
            if(!empty($request->templates)){
                    $query->whereIn('posted_audits.template',$request->templates);
                }
            })
            ->whereIn('posted_audits.template',$templates)
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

    public static function getUserSummary($request = null,$use){
        $users = '';
        $audits = '';
        $pjps = '';
        $usser=[];
        $users_str = '';
        foreach($use as $u) {
            $usser[]=$u->user_id;
        }      
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
        if(!empty($usser)){            
            $users_str = " and posted_audits.user_id in (". implode(",",$usser). ")";
        }
        
        $query = sprintf('
            select users.id as user_id, posted_audits.audit_id,users.name, audits.description,
            COALESCE(tbl_mapped.mapped_stores,0) as mapped_stores, tbl_posted.store_visited,
            (sum(osa) / count(*))  as osa_ave,
            (sum(npi) / count(*)) as npi_ave,
            (sum(planogram) / count(*)) as planogram_ave
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
            %s %s %s
            group by posted_audits.user_id, posted_audits.audit_id
            order by audits.description, users.name',$pjps ,$pjps, $users,$audits,$users_str);

        $data = DB::select(DB::raw($query));

        foreach ($data as $key => $value) {
            $audit = Audit::findOrFail($value->audit_id);
            $user = User::findOrFail($value->user_id);
            $summary = UserSummary::getSummary($audit,$user);

            $data[$key]->perfect_store = $summary->detail->perfect_store_count;
            $pjp_target = AuditUserPjp::where('user_id', $value->user_id)
                ->where('audit_id', $value->audit_id)
                ->first();

            $data[$key]->target = 0;

            if(!empty($pjp_target)){
                $data[$key]->target = $pjp_target->target;
            }
            if($value->target != 0){
                $data[$key]->pjp_compliance = number_format(($value->store_visited/$value->target) * 100,2);    
            }

            if($value->target === 0){
                $data[$key]->pjp_compliance = 0;   
            }            
            $audit = Audit::findOrFail($value->audit_id);
            $user = User::findOrFail($value->user_id);
            $usersummary = UserSummary::getSummary($audit,$user);                        
            $detail = $usersummary->detail;
            $data[$key]->ps_doors = number_format(($value->perfect_store/$value->store_visited) * 100,2);             
            $data[$key]->ps_cat = number_format((float)$detail->category_door_per,2,'.',',');
            $data[$key]->osa = number_format((float)$value->osa_ave,2,'.',',');
            $data[$key]->npi = number_format((float)$value->npi_ave,2,'.',',');
            $data[$key]->planogram = number_format((float)$value->planogram_ave,2,'.',',');
        }

        return $data;
    }

    public static function getUserSummaryDetails($audit_id,$user_id){
        $query = sprintf('
            select users.id as user_id, posted_audits.audit_id,users.name, audits.description,
            tbl_mapped.mapped_stores, tbl_posted.store_visited, coalesce(tbl_target.target, 0) as pjptarget
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
            left join (
                select * from audit_user_pjps
            ) as tbl_target using(user_id,audit_id)
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

    public static function getCustomerSummary($request = null,$temp,$cust,$use){
        $customers = '';
        $regions = '';
        $templates = '';
        $audits = '';
        $pjps = '';        
        $temps =[];
        $custs = [];                
        $temps_str = '';
        $custs_str = '';
        $uid = '';
        $user_id = [];

        foreach($use as $u){
            $user_id[]=$u->user_id;
        }

        foreach($temp as $t) {
            $temps[]=$t->template;
        }  
        foreach($cust as $c) {
            $custs[]=$c->customer_code;
        }          
        if(!empty($temps)){
            $temps_str = "and posted_audits.template in ('". implode("','", $temps) ."')";
        }
        if(!empty($custs)){
            $custs_str = "and customer_code in ('". implode("','", $custs) ."')";
        }

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
        if(!empty($user_id)){
            $uid = "and posted_audits.user_id in (". implode(",",$user_id). ")";
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
            %s
            %s
            %s
            group by audit_id, channel_code, region_code',$customers,$regions,$templates,$audits,$temps_str,$custs_str,$uid);

        $data = DB::select(DB::raw($query));        
        // dd($data);

        foreach ($data as $key => $value) {
            $stores = self::getCustomerStores($value->audit_id,$value->channel_code,$value->region_code,$value->customer_code);
            $perfect_stores = 0;
            $total_perfect_store_percentage = 0;
            foreach ($stores as $store) {
                $total_perfect_store_percentage += $store->perfect_percentage;
                if($store->perfect_percentage == '100.00'){
                    $perfect_stores++;
                }
            }
            $data[$key]->perfect_stores = $perfect_stores;
            $data[$key]->ave_perfect_stores = number_format($total_perfect_store_percentage / count($stores),2);
            $data[$key]->ps_doors = number_format((float)$value->perfect_stores/$value->visited_stores,2,'.',',')*100;            
            $data[$key]->ps_doors = number_format((float)$value->perfect_stores/$value->visited_stores,2,'.',',')*100;            
            $data[$key]->osa = number_format((float)$value->osa_ave,2,'.',',');
            $data[$key]->npi = number_format((float)$value->npi_ave,2,'.',',');
            $data[$key]->planogram = number_format((float)$value->planogram_ave,2,'.',',');
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

    public static function getOsaSku($request,$use){
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
        //owaowa
        $usser = [];
        $users_str = '';
        foreach($use as $u)
        {
            $usser[]=$u->user_id;
        }
        if(!empty($usser)){            
            $users_str = " and posted_audits.user_id in (". implode(",",$usser). ")";
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
            %s
            group by prompt,posted_audits.channel_code
            order by osa_percent, audit_id, template',$osas,$audits,$templates, $customers, $categories,$users_str);

        return DB::select(DB::raw($query));
    }

    public static function OsaStoresNotAvail($detail){                
        $cat_str = $detail['category'];
        $cust_str = $detail['customer'];    
        $temp_str =  $detail['template'];
        $sku_str =  $detail['prompt'];
        $des_str = $detail['description'];        
        $osas = '';
        $osa_desc = [];
        $formgroups = FormGroup::where('osa',1)->get();
        foreach ($formgroups as $group) {
            $osa_desc[] = $group->group_desc;
        }
        return DB::table('posted_audits')
            ->select('posted_audits.template','posted_audits.store_name','posted_audit_details.*','posted_audits.customer','posted_audits.id','posted_audits.channel_code','audits.*')
            ->join('posted_audit_details','posted_audit_details.posted_audit_id','=','posted_audits.id')
            ->join('audits','audits.id','=','posted_audits.audit_id')
            ->where('posted_audit_details.type' ,"CONDITIONAL")
            ->where('posted_audit_details.answer','!=',"AVAILABLE ON SHELF")
            ->where('posted_audits.template',$temp_str)
            ->where('posted_audits.customer',$cust_str)
            ->where('posted_audit_details.category',$cat_str)
            ->where('posted_audit_details.prompt',$sku_str)
            ->where('audits.description',$des_str)
            ->whereIn('posted_audit_details.group',$osa_desc)
            ->groupBy('store_name')
            ->orderBy('posted_audits.id')
            ->get();

    }

    public static function getNpiSku($request,$use){
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
        $usser = [];
        $users_str = '';
        foreach($use as $u)
        {
            $usser[]=$u->user_id;
        }
        if(!empty($usser)){            
            $users_str = " and posted_audits.user_id in (". implode(",",$usser). ")";
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
            %s
            group by prompt,posted_audits.channel_code
            order by osa_percent, audit_id, template',$npis,$audits,$templates, $categories, $customers,$users_str);

        return DB::select(DB::raw($query));
    }
    public static function NpiStoresNotAvail($detail){                
        $cat_str = $detail['category'];
        $cust_str = $detail['customer'];    
        $temp_str =  $detail['template'];
        $sku_str =  $detail['prompt'];
        $des_str = $detail['description'];        
        $npis = '';
        $npi_desc = [];
        $formgroups = FormGroup::where('npi',1)->get();
        foreach ($formgroups as $group) {
            $npi_desc[] = $group->group_desc;
        }
        return DB::table('posted_audits')
            ->select('posted_audits.template','posted_audits.store_name','posted_audit_details.*','posted_audits.customer','posted_audits.id','posted_audits.channel_code','audits.*')
            ->join('posted_audit_details','posted_audit_details.posted_audit_id','=','posted_audits.id')
            ->join('audits','audits.id','=','posted_audits.audit_id')
            ->where('posted_audit_details.type' ,"CONDITIONAL")
            ->where('posted_audit_details.answer','!=',"AVAILABLE ON SHELF")
            ->where('posted_audits.template',$temp_str)
            ->where('posted_audits.customer',$cust_str)
            ->where('posted_audit_details.category',$cat_str)
            ->where('posted_audit_details.prompt',$sku_str)
            ->where('audits.description',$des_str)
            ->whereIn('posted_audit_details.group',$npi_desc)
            ->groupBy('store_name')
            ->orderBy('posted_audits.id')
            ->get();

    }

    public static function getCustomizedPlanoSku($request,$use){
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
        $usser = [];
        $users_str = '';
        foreach($use as $u)
        {
            $usser[]=$u->user_id;
        }
        if(!empty($usser)){            
            $users_str = " and posted_audits.user_id in (". implode(",",$usser). ")";
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
            %s
            group by prompt,posted_audits.channel_code
            order by osa_percent, audit_id, template',$planos,$audits,$templates, $categories, $customers,$users_str);

        return DB::select(DB::raw($query));
    }
    public static function PlanoStoresNotAvail($detail){                
        $cat_str = $detail['category'];
        $cust_str = $detail['customer'];    
        $temp_str =  $detail['template'];
        $sku_str =  $detail['prompt'];
        $des_str = $detail['description'];        
        $planos = '';
        $plano_desc = [];
        $formgroups = FormGroup::where('plano',1)->get();
        foreach ($formgroups as $group) {
            $plano_desc[] = $group->group_desc;
        }
        return DB::table('posted_audits')
            ->select('posted_audits.template','posted_audits.store_name','posted_audit_details.*','posted_audits.customer','posted_audits.id','posted_audits.channel_code','audits.*')
            ->join('posted_audit_details','posted_audit_details.posted_audit_id','=','posted_audits.id')
            ->join('audits','audits.id','=','posted_audits.audit_id')
            ->where('posted_audit_details.type' ,"CONDITIONAL")
            ->where('posted_audit_details.answer','!=',"IMPLEMENTED")
            ->where('posted_audits.template',$temp_str)
            ->where('posted_audits.customer',$cust_str)
            ->where('posted_audit_details.category',$cat_str)
            ->where('posted_audit_details.prompt',$sku_str)
            ->where('audits.description',$des_str)
            ->whereIn('posted_audit_details.group',$plano_desc)
            ->groupBy('store_name')
            ->orderBy('posted_audits.id')
            ->get();

    }

    public static function getSos($request = null,$use){
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
        $usser = [];
        $users_str = '';
        foreach($use as $u)
        {
            $usser[]=$u->user_id;
        }
        if(!empty($usser)){            
            $users_str = " and posted_audits.user_id in (". implode(",",$usser). ")";
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
            %s
            order by audit_id, store_name, category", $soss,$audits, $customers, $templates, $stores, $categories, $users,$users_str);

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
    public static function getPerfectStoreAverage($posted_audits){

        $counter = 0;
        $count = 0;
        $total_posted = 0.00;
        $total_average = 0.00;
        foreach($posted_audits as $posted){
            $add = $posted->perfect_percentage;
            $total_posted = $total_posted + $add;
            $counter++;
            $count++;
        }                

        
        
        if($count ===0)
        {
            $total_average = 0;

        }            
        else{
            $total_average = number_format(($total_posted/$count),2,'.',',');
        }
        return $total_average;
    }
    public static function getPerfectStoreAverageInCustomerReport($posted_audits){        
        $counter = 0;
        $count = 0;
        $total_posted = 0.00;
        $total_average = 0.00;
        foreach($posted_audits as $posted){
            $add = $posted->ave_perfect_stores;
            $total_posted = $total_posted + $add;
            $counter++;
            $count++;
        }
         if($count ===0)
        {
            $total_average = 0;

        } 
        else{
            $total_average = number_format(($total_posted/$count),2,'.',',');     
        }
               
        return $total_average;
    }
    public static function getPerfectStoreAverageInUserReport($posted_audits){        
        $counter = 0;
        $count = 0;
        $total_posted = 0.00;
        $total_average = 0.00;
        foreach($posted_audits as $posted){
            $add = $posted->ps_cat;
            $total_posted = $total_posted + $add;
            $counter++;
            $count++;
        }
         if($count ===0)
        {
            $total_average = 0;

        } 
        else{
            $total_average = number_format(($total_posted/$count),2,'.',',');     
        }
               
        return $total_average;
    }
    public static function getOsaAverage($posted_audits){
        $counter = 0;
        $count = 0;
        $total_posted = 0.00;
        $total_average = 0.00;
        foreach($posted_audits as $posted){
            $add = $posted->osa;
            $total_posted = $total_posted + $add;
            $counter++;
            $count++;
        }
           if($count ===0)
        {
            $total_average = 0;

        } 
        else{
        $total_average = number_format(($total_posted/$count),2,'.',',');
        }
        return $total_average;
    }
    public static function getNpiAverage($posted_audits){
        $counter = 0;
        $count = 0;
        $total_posted = 0.00;
        $total_average = 0.00;
        foreach($posted_audits as $posted){
            $add = $posted->npi;
            $total_posted = $total_posted + $add;
            $counter++;
            $count++;
        }
        if($count ===0)
        {
            $total_average = 0;

        } 
        else{        
        $total_average =number_format(($total_posted/$count),2,'.',',');
        }
        return $total_average;
    }

    public static function getPlanogramAverage($posted_audits){
        $counter = 0;
        $count = 0;
        $total_posted = 0.00;
        $total_average = 0.00;
        foreach($posted_audits as $posted){
            $add = $posted->planogram;
            $total_posted = $total_posted + $add;
            $counter++;
            $count++;
        }
        if($count ===0)
        {
            $total_average = 0;

        } 
        else{        
        $total_average = number_format(($total_posted/$count),2,'.',',');
    }
        // $total_average == 0 ? 0 :number_format((float)$total_posted/$count,2,'.',',');
        return $total_average;
    }

    public static function searchDefault($use){
        $templates = '';
        $users=[];
        $auth_user = Auth::id();
        $role = DB::table('role_user')
            ->select('role_id')
            ->where('user_id',$auth_user)
            ->first(); 
        if($role->role_id === 4){
            foreach($use as $u) {
                $users[]=$u->user_id;
            }        
            $data = self::select(DB::raw('posted_audits.*, audit_stores.pjp, audit_stores.freq'))
                ->whereIn('posted_audits.user_id',$users)
                ->leftJoin('audit_stores', function($join){
                    $join->on('audit_stores.audit_id', '=', 'posted_audits.audit_id');
                    $join->on('audit_stores.store_code','=','posted_audits.store_code');
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
        else{
            $data = self::select(DB::raw('posted_audits.*, audit_stores.pjp, audit_stores.freq'))            
            ->leftJoin('audit_stores', function($join){
                $join->on('audit_stores.audit_id', '=', 'posted_audits.audit_id');
                $join->on('audit_stores.store_code','=','posted_audits.store_code');
            })            
            ->orderBy('posted_audits.updated_at','desc')
            ->paginate(100);
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
        
        
    }
    public static function searchDefaultMT($temp){
        $templates = '';
        $templates=[];
        foreach($temp as $t) {
            $templates[]=$t->template;
        }        
        $data = self::select(DB::raw('posted_audits.*, audit_stores.pjp, audit_stores.freq'))
            ->whereIn('posted_audits.template',$templates)
            ->leftJoin('audit_stores', function($join){
                $join->on('audit_stores.audit_id', '=', 'posted_audits.audit_id');
                $join->on('audit_stores.store_code','=','posted_audits.store_code');
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

     public static function getUserSummaryDefault($use){
        $users = '';
        $audits = '';
        $pjps = '';        
        $user=[];
        foreach($use as $u) {
            $user[]=$u->user_id;
        }        
        
        $u = DB::table('posted_audits')
            ->select('posted_audits.*')
            ->whereIn('posted_audits.user_id',$user)            
            ->groupBy('user_id')
            ->get();            

        $p = [];

        foreach($u as $pl){
            $p[] =$pl->user_id; 
        }

        $users_str = "";
        if(!empty($p)){            
            $users_str = " and posted_audits.user_id in (". implode(",",$p). ")";
        }                
        $query = sprintf('
            select users.id as user_id, posted_audits.audit_id,users.name, audits.description,            
            COALESCE(tbl_mapped.mapped_stores,0) as mapped_stores, tbl_posted.store_visited,
            (sum(osa) / count(*))  as osa_ave,
            (sum(npi) / count(*)) as npi_ave,
            (sum(planogram) / count(*)) as planogram_ave
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
            %s
            %s 
            %s
            group by posted_audits.user_id, posted_audits.audit_id
            order by audits.description, users.name',$pjps ,$pjps, $users,$audits, $users_str);
        // $query = sprintf('
        //     select users.id as user_id, posted_audits.audit_id,users.name, audits.description,
        //     COALESCE(tbl_mapped.mapped_stores,0) as mapped_stores, tbl_posted.store_visited
        //     from posted_audits
        //     %s            
        //     inner join users on users.id = posted_audits.user_id
        //     inner join audits on audits.id = posted_audits.audit_id
        //     left join (
        //         select user_id, audit_id, count(*) as mapped_stores from audit_stores
        //         %s
        //         group by user_id, audit_id
        //     ) as tbl_mapped using(user_id,audit_id)
        //     left join (
        //         select posted_audits.user_id,posted_audits.audit_id,count(*) as store_visited 
        //         from `posted_audits`
        //         left join audit_stores on (audit_stores.audit_id = posted_audits.audit_id  and audit_stores.store_code = posted_audits.store_code)
        //         %s
        //         group by posted_audits.user_id, posted_audits.audit_id
        //     ) as tbl_posted using(user_id,audit_id)
        //     where tbl_posted.store_visited > 0
        //     %s %s
        //     group by posted_audits.user_id, posted_audits.audit_id
        //     order by audits.description, users.name',$users_str,$pjps ,$pjps, $users,$audits);
        
    
        $data = DB::select(DB::raw($query));        
        
        foreach ($data as $key => $value) {
            $audit = Audit::findOrFail($value->audit_id);
            $user = User::findOrFail($value->user_id);
            $summary = UserSummary::getSummary($audit,$user);

            $data[$key]->perfect_store = $summary->detail->perfect_store_count;
            $pjp_target = AuditUserPjp::where('user_id', $value->user_id)
                ->where('audit_id', $value->audit_id)
                ->first();

            $data[$key]->target = 0;

            if(!empty($pjp_target)){
                $data[$key]->target = $pjp_target->target;
            }

            if($value->target != 0){
                $data[$key]->pjp_compliance = number_format(($value->store_visited/$value->target) * 100,2);    
            }

            if($value->target === 0){
                $data[$key]->pjp_compliance = 0;   
            }            
            $audit = Audit::findOrFail($value->audit_id);
            $user = User::findOrFail($value->user_id);
            $usersummary = UserSummary::getSummary($audit,$user);                        
            $detail = $usersummary->detail;
            $data[$key]->ps_doors = number_format(($value->perfect_store/$value->store_visited) * 100,2);             
            $data[$key]->ps_cat = number_format((float)$detail->category_door_per,2,'.',',');
            $data[$key]->osa = number_format((float)$value->osa_ave,2,'.',',');
            $data[$key]->npi = number_format((float)$value->npi_ave,2,'.',',');
            $data[$key]->planogram = number_format((float)$value->planogram_ave,2,'.',',');
        }        
        return $data;
    }      
     public static function getCustomerSummaryDefault($use){
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
        $custom = '';
        $c = [];        
        foreach($use as $u){
            $c[]=$u->user_id;
        }        

        if(!empty($c)){
            $custom = "and posted_audits.user_id in (". implode(",",$c). ")";
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
            %s
            group by audit_id, channel_code, region_code',$customers,$regions,$templates,$audits,$custom);
        
        $data = DB::select(DB::raw($query));        
        // dd($data);

        foreach ($data as $key => $value) {
            $stores = self::getCustomerStores($value->audit_id,$value->channel_code,$value->region_code,$value->customer_code);
            $perfect_stores = 0;
            $total_perfect_store_percentage = 0;
            foreach ($stores as $store) {
                $total_perfect_store_percentage += $store->perfect_percentage;
                if($store->perfect_percentage == '100.00'){
                    $perfect_stores++;
                }
            }
            $data[$key]->perfect_stores = $perfect_stores;
            $data[$key]->ave_perfect_stores = number_format($total_perfect_store_percentage / count($stores),2);
            $data[$key]->ps_doors = number_format((float)$value->perfect_stores/$value->visited_stores,2,'.',',')*100;            
            $data[$key]->osa = number_format((float)$value->osa_ave,2,'.',',');
            $data[$key]->npi = number_format((float)$value->npi_ave,2,'.',',');
            $data[$key]->planogram = number_format((float)$value->planogram_ave,2,'.',',');
        }

        return $data;

    }

    public static function getsfilters($auth_user,$cus){
        //fields na naka map sa user
        $myFields = DB::table('manager_fields')
            ->select('manager_fields.*')
            ->where('manager_fields.managers_id','=',$auth_user)
            ->get();

        $data = [];
        foreach ($myFields as $value) {
            $data[] =  $value->fields_id;
        }        
        //templates na naka mapped sa user
        $myTemplates = DB::table('manager_templates')
            ->select('manager_templates.*')
            ->where('manager_templates.managers_id','=',$auth_user)
            ->get();
        $datas = [];

        foreach ($myTemplates as $value) {
            $datas[] =  $value->templates_id;
        }
        $temp_desc = DB::table('templates')
            ->select('templates.*')
            ->whereIn('templates.id',$datas)            
            ->get();
        $tdes =[];
        foreach($temp_desc as $td){ 
            $tdes[]=$td->description;            
        }
        $another_user = DB::table('posted_audits')
            ->select('posted_audits.*')
            ->whereIn('posted_audits.template',$tdes)
            ->get();         
        foreach ($another_user as $value) {
            $data[] =  $value->user_id;
        }  
                
        $custom = [];  
        foreach($cus as $c){
            $custom = $c;
        }
        return self::select('user_id', 'users.name','customer_code')
            ->whereIn('user_id',$data)
            ->whereIn('customer_code',$custom)      
            ->join('users','users.id', '=', 'posted_audits.user_id')                    
            ->groupBy('user_id')
            ->orderBy('users.name')
            ->get();                
    }
   
    public static function getsstorefilters($cus,$use){
        $users=[];
        foreach($use as $u) {
            $users[]=$u->user_id;
        }        
        $custom = [];
        foreach($cus as $c){
            $custom = $c;
        }
        return self::select('store_code', 'store_name','user_id')
            ->whereIn('user_id',$users)
            ->whereIn('customer_code',$custom)
            ->groupBy('store_code')
            ->orderBy('store_name')
            ->get();
    }
    public static function getstemplatefilters($auth_user,$cus){
        $role = DB::table('role_user')
            ->select('role_id')
            ->where('user_id',$auth_user)
            ->first(); 
        if($role->role_id === 3){
            if(!empty($cus)){

                $myTemplates = DB::table('manager_templates')
                    ->join('templates', 'templates.id', '=', 'manager_templates.templates_id')
                    ->where('manager_templates.managers_id','=',$auth_user)
                    ->get();    
                $custom = [];
                foreach($cus as $c){
                    $custom[] = $c;
                }
                $temp = [];
                foreach($myTemplates as $c){
                    $temp[] = $c->description;
                }
                
                return self::select('channel_code', 'template')
                    ->whereIn('template',$temp)            
                    ->whereIn('customer_code',$custom)
                    ->groupBy('template')
                    ->orderBy('template')
                    ->get();
            }
            if(empty($cus)){
                 $myTemplates = DB::table('manager_templates')
                    ->join('templates', 'templates.id', '=', 'manager_templates.templates_id')
                    ->where('manager_templates.managers_id','=',$auth_user)
                    ->get();                   
                $temp = [];
                foreach($myTemplates as $c){
                    $temp[] = $c->description;
                }
                
                return self::select('channel_code', 'template')
                    ->whereIn('template',$temp)                                
                    ->groupBy('template')
                    ->orderBy('template')
                    ->get();
            }
        }
        if($role->role_id === 1 || $role->role_id === 2){
            if(!empty($cus)){
                $custom = [];
                foreach($cus as $c){
                    $custom[] = $c;
                }           
                return self::select('channel_code', 'template')                        
                    ->whereIn('customer_code',$custom)
                    ->groupBy('template')
                    ->orderBy('template')
                    ->get();
            }
            if(empty($cus)){
                return self::select('channel_code', 'template')                                            
                    ->groupBy('template')
                    ->orderBy('template')
                    ->get();
            }
            
        }
    }
   
}
