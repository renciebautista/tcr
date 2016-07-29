<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AuditTemplateCategory;
use DB;
use App\Role;
use Auth;
class FormCategory extends Model
{
    protected $fillable = ['audit_id','category', 'sos', 'second_display', 'osa', 'custom', 'perfect_store'];
    public $timestamps = false;

    public function secondarybrand(){
        return $this->hasMany('App\AuditSecondaryDisplay', 'form_category_id', 'id');
    }

    public static function secondaryCategories($audit,$store){
    	$data = self::where('audit_id', $audit->id)->where('second_display', 1)->get();

        foreach ($data as $key => $value) {
            $data[$key]->brands = AuditSecondaryDisplay::where('audit_id',$audit->id)
                ->where('customer',$store->customer)
                ->where('form_category_id',$value->id)
                ->get();
        }

        return $data;
    }

    public static function osaCategories($audit){
    	return self::where('audit_id', $audit->id)->where('osa', 1)->get();
    }

    public static function sosCategories($audit){
        return self::where('audit_id', $audit->id)->where('sos', 1)->get();
    }

    public static function getTemplateCategory($id,$audit_id,$perfect_store = null){
        
        $data = AuditTemplateCategory::select('form_category_id')
            ->where('audit_template_id',$id)
            ->groupBy('form_category_id')
            ->get();

        $ids = [];
        foreach ($data as $value) {
            $ids[] = $value->form_category_id;
        }

        return self::where('audit_id',$audit_id)
            ->whereIn('id',$ids)
            ->where(function($query) use ($perfect_store){
            if(!empty($perfect_store )){
                    $query->where('perfect_store',1);
                }
            })
            ->get();
    }

    public static function getSOSCategories($use){
        $user_id =[];
        foreach($use as $c){
            $user_id[]=$c->user_id;
        }
        $category_id = [];        
        $userd = DB::table('posted_audits')
            ->select('posted_audits.*')
            ->whereIn('user_id',$user_id)
            ->get();
        $audid = [];
        foreach($userd as $ud){
            $audid[]=$ud->id;
        }        
        $catid = DB::table('posted_audit_details')
            ->select('posted_audit_details.*')
            ->whereIn('posted_audit_details.posted_audit_id',$audid)
            ->groupBy('category')
            ->get();
        $cat =[];        
        foreach($catid as $cid){
            $cat[]= $cid->category;
        }        
        return self::select('category', 'category')
            ->where('sos', 1)
            ->whereIn('category',$cat)
            ->groupBy('category')
            ->orderBy('category')
            ->get();
    }
    public static function getSOSCategoriesMT($temp){
        $user_id =[];
        foreach($temp as $c){
            $user_id[]=$c->template;
        }
        $category_id = [];        
        $userd = DB::table('posted_audits')
            ->select('posted_audits.*')
            ->whereIn('template',$user_id)
            ->get();
        $audid = [];
        foreach($userd as $ud){
            $audid[]=$ud->id;
        }        
        $catid = DB::table('posted_audit_details')
            ->select('posted_audit_details.*')
            ->whereIn('posted_audit_details.posted_audit_id',$audid)
            ->groupBy('category')
            ->get();
        $cat =[];        
        foreach($catid as $cid){
            $cat[]= $cid->category;
        }        
        return self::select('category', 'category')
            ->where('sos', 1)
            ->whereIn('category',$cat)
            ->groupBy('category')
            ->orderBy('category')
            ->get();
    }

    public static function getOSACategories($use){
        $user_id =[];
        foreach($use as $c){
            $user_id[]=$c->user_id;
        }
        $category_id = [];        
        $userd = DB::table('posted_audits')
            ->select('posted_audits.*')
            ->whereIn('user_id',$user_id)
            ->get();
        $audid = [];
        foreach($userd as $ud){
            $audid[]=$ud->id;
        }        
        $catid = DB::table('posted_audit_details')
            ->select('posted_audit_details.*')
            ->whereIn('posted_audit_details.posted_audit_id',$audid)
            ->groupBy('category')
            ->get();
        $cat =[];        
        foreach($catid as $cid){
            $cat[]= $cid->category;
        }        
        return self::select('category', 'category')
            ->where('osa', 1)
            ->whereIn('category',$cat)
            ->groupBy('category')
            ->orderBy('category')
            ->get();
    }

    public static function OsaCatFilter($customer,$template,$use){
        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);

        $user_id =[];
        $customer_code = [];
        $template_code = [];
        
        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){
        
            foreach($use as $c){

                $user_id[]=$c->user_id;
            }

        }
        if($role->role_id === 3){

            foreach($use as $c){
                
                $template_code[]=$c->template;
            }

        }
        
        if(!empty($customer) && !empty($template)){       

            if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){                       

                $chan = DB::table('posted_audit_details')
                        ->select('posted_audit_details.*','posted_audits.*')
                        ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                        ->whereIn('posted_audits.channel_code',$template)
                        ->whereIn('posted_audits.customer_code',$customer)
                        ->whereIn('posted_audits.user_id',$user_id)
                        ->groupBy('posted_audit_details.category')
                        ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')
                    ->where('osa', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();     
            }
            if($role->role_id === 3){

                $chan = DB::table('posted_audit_details')
                        ->select('posted_audit_details.*','posted_audits.*')
                        ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                        ->whereIn('posted_audits.channel_code',$template)
                        ->whereIn('posted_audits.customer_code',$customer)
                        ->whereIn('posted_audits.template',$template_code)
                        ->groupBy('posted_audit_details.category')
                        ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')
                    ->where('osa', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();    
            }
        }

        if(!empty($customer) && empty($template)){

            if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

                $chan = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')                    
                    ->whereIn('posted_audits.user_id',$user_id)
                    ->whereIn('posted_audits.customer_code',$customer)
                    ->groupBy('posted_audit_details.category')
                    ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')                
                    ->where('osa', 1)
                    ->whereIn('category',$cat)                
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();
            }
            if($role->role_id === 3){

                $chan = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')                    
                    ->whereIn('posted_audits.template',$template_code)
                    ->whereIn('posted_audits.customer_code',$customer)
                    ->groupBy('posted_audit_details.category')
                    ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')                
                    ->where('osa', 1)
                    ->whereIn('category',$cat)                
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

            }
        }

        if(empty($customer) && !empty($template)){

            if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){                        

                $chan = DB::table('posted_audit_details')
                        ->select('posted_audit_details.*','posted_audits.*')
                        ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                        ->whereIn('posted_audits.channel_code',$template)                    
                        ->whereIn('posted_audits.user_id',$user_id)
                        ->groupBy('posted_audit_details.category')
                        ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')
                    ->where('osa', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();
            }

            if($role->role_id === 3){                    

                $chan = DB::table('posted_audit_details')
                        ->select('posted_audit_details.*','posted_audits.*')
                        ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                        ->whereIn('posted_audits.channel_code',$template)                    
                        ->whereIn('posted_audits.template',$template_code)
                        ->groupBy('posted_audit_details.category')
                        ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')
                    ->where('osa', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

            }


        }

        if(empty($customer) && empty($template)){

            $category_id = [];                

            $chan = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')                    
                    ->whereIn('posted_audits.user_id',$user_id)
                    ->groupBy('posted_audit_details.category')
                    ->get();

            $cat =[];

            foreach($chan as $cid){

                $cat[]= $cid->category;
            }         

            return self::select('category', 'category')
                ->where('osa', 1)
                ->whereIn('category',$cat)
                ->groupBy('category')
                ->orderBy('category')
                ->get();

        }

    }
    public static function getNPICategories($use){
        $user_id =[];
        foreach($use as $c){
            $user_id[]=$c->user_id;
        }
        $category_id = [];        
        $userd = DB::table('posted_audits')
            ->select('posted_audits.*')
            ->whereIn('user_id',$user_id)
            ->get();
        $audid = [];
        foreach($userd as $ud){
            $audid[]=$ud->id;
        }        
        $catid = DB::table('posted_audit_details')
            ->select('posted_audit_details.*')
            ->whereIn('posted_audit_details.posted_audit_id',$audid)
            ->groupBy('category')
            ->get();
        $cat =[];        
        foreach($catid as $cid){
            $cat[]= $cid->category;
        }        
        return self::select('category', 'category')
            ->where('npi', 1)
            ->whereIn('category',$cat)
            ->groupBy('category')
            ->orderBy('category')
            ->get();
    }
    public static function NpiCatFilter($customer,$template,$use){
        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);

        $user_id =[];
        $customer_code = [];
        $template_code = [];
        
        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){
        
            foreach($use as $c){

                $user_id[]=$c->user_id;
            }

        }
        if($role->role_id === 3){

            foreach($use as $c){
                
                $template_code[]=$c->template;
            }

        }
        
        if(!empty($customer) && !empty($template)){       

            if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){                       

                $chan = DB::table('posted_audit_details')
                        ->select('posted_audit_details.*','posted_audits.*')
                        ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                        ->whereIn('posted_audits.channel_code',$template)
                        ->whereIn('posted_audits.customer_code',$customer)
                        ->whereIn('posted_audits.user_id',$user_id)
                        ->groupBy('posted_audit_details.category')
                        ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')
                    ->where('npi', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();     
            }
            if($role->role_id === 3){

                $chan = DB::table('posted_audit_details')
                        ->select('posted_audit_details.*','posted_audits.*')
                        ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                        ->whereIn('posted_audits.channel_code',$template)
                        ->whereIn('posted_audits.customer_code',$customer)
                        ->whereIn('posted_audits.template',$template_code)
                        ->groupBy('posted_audit_details.category')
                        ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')
                    ->where('npi', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();    
            }
        }

        if(!empty($customer) && empty($template)){

            if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

                $chan = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')                    
                    ->whereIn('posted_audits.user_id',$user_id)
                    ->whereIn('posted_audits.customer_code',$customer)
                    ->groupBy('posted_audit_details.category')
                    ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')                
                    ->where('npi', 1)
                    ->whereIn('category',$cat)                
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();
            }
            if($role->role_id === 3){

                $chan = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')                    
                    ->whereIn('posted_audits.template',$template_code)
                    ->whereIn('posted_audits.customer_code',$customer)
                    ->groupBy('posted_audit_details.category')
                    ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')                
                    ->where('npi', 1)
                    ->whereIn('category',$cat)                
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

            }
        }

        if(empty($customer) && !empty($template)){

            if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){                        

                $chan = DB::table('posted_audit_details')
                        ->select('posted_audit_details.*','posted_audits.*')
                        ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                        ->whereIn('posted_audits.channel_code',$template)                    
                        ->whereIn('posted_audits.user_id',$user_id)
                        ->groupBy('posted_audit_details.category')
                        ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')
                    ->where('npi', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();
            }

            if($role->role_id === 3){                    

                $chan = DB::table('posted_audit_details')
                        ->select('posted_audit_details.*','posted_audits.*')
                        ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                        ->whereIn('posted_audits.channel_code',$template)                    
                        ->whereIn('posted_audits.template',$template_code)
                        ->groupBy('posted_audit_details.category')
                        ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')
                    ->where('npi', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

            }


        }

        if(empty($customer) && empty($template)){

            $category_id = [];                

            $chan = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')                    
                    ->whereIn('posted_audits.user_id',$user_id)
                    ->groupBy('posted_audit_details.category')
                    ->get();

            $cat =[];

            foreach($chan as $cid){

                $cat[]= $cid->category;
            }         

            return self::select('category', 'category')
                ->where('npi', 1)
                ->whereIn('category',$cat)
                ->groupBy('category')
                ->orderBy('category')
                ->get();

        }
    }

    public static function getPlanoCategories($use){
        $user_id =[];
        foreach($use as $c){
            $user_id[]=$c->user_id;
        }
        $category_id = [];        
        $userd = DB::table('posted_audits')
            ->select('posted_audits.*')
            ->whereIn('user_id',$user_id)
            ->get();
        $audid = [];
        foreach($userd as $ud){
            $audid[]=$ud->id;
        }        
        $catid = DB::table('posted_audit_details')
            ->select('posted_audit_details.*')
            ->whereIn('posted_audit_details.posted_audit_id',$audid)
            ->groupBy('category')
            ->get();
        $cat =[];        
        foreach($catid as $cid){
            $cat[]= $cid->category;
        }        
        return self::select('category', 'category')
            ->where('plano', 1)
            ->whereIn('category',$cat)
            ->groupBy('category')
            ->orderBy('category')
            ->get();
    }
    public static function getPlanoCategoriesMT($temp){
        $user_id =[];
        foreach($temp as $c){
            $user_id[]=$c->template;
        }
        $category_id = [];        
        $userd = DB::table('posted_audits')
            ->select('posted_audits.*')
            ->whereIn('template',$user_id)
            ->get();
        $audid = [];
        foreach($userd as $ud){
            $audid[]=$ud->id;
        }        
        $catid = DB::table('posted_audit_details')
            ->select('posted_audit_details.*')
            ->whereIn('posted_audit_details.posted_audit_id',$audid)
            ->groupBy('category')
            ->get();
        $cat =[];        
        foreach($catid as $cid){
            $cat[]= $cid->category;
        }        
        return self::select('category', 'category')
            ->where('plano', 1)
            ->whereIn('category',$cat)
            ->groupBy('category')
            ->orderBy('category')
            ->get();
    }
    public static function getNpiCategoriesMT($temp){
        $user_id =[];
        foreach($temp as $c){
            $user_id[]=$c->template;
        }
        $category_id = [];        
        $userd = DB::table('posted_audits')
            ->select('posted_audits.*')
            ->whereIn('template',$user_id)
            ->get();
        $audid = [];
        foreach($userd as $ud){
            $audid[]=$ud->id;
        }        
        $catid = DB::table('posted_audit_details')
            ->select('posted_audit_details.*')
            ->whereIn('posted_audit_details.posted_audit_id',$audid)
            ->groupBy('category')
            ->get();
        $cat =[];        
        foreach($catid as $cid){
            $cat[]= $cid->category;
        }        
        return self::select('category', 'category')
            ->where('npi', 1)
            ->whereIn('category',$cat)
            ->groupBy('category')
            ->orderBy('category')
            ->get();
    }
     public static function getOSACategoriesMT($temp){
        $user_id =[];
        foreach($temp as $c){
            $user_id[]=$c->template;
        }
        $category_id = [];        
        $userd = DB::table('posted_audits')
            ->select('posted_audits.*')
            ->whereIn('template',$user_id)
            ->get();
        $audid = [];
        foreach($userd as $ud){
            $audid[]=$ud->id;
        }        
        $catid = DB::table('posted_audit_details')
            ->select('posted_audit_details.*')
            ->whereIn('posted_audit_details.posted_audit_id',$audid)
            ->groupBy('category')
            ->get();
        $cat =[];        
        foreach($catid as $cid){
            $cat[]= $cid->category;
        }        
        return self::select('category', 'category')
            ->where('osa', 1)
            ->whereIn('category',$cat)
            ->groupBy('category')
            ->orderBy('category')
            ->get();
    }
    public static function PlaCatFilter($customer,$template,$use){
        
        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);

        $user_id =[];
        $customer_code = [];
        $template_code = [];
        
        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){
        
            foreach($use as $c){
                $user_id[]=$c->user_id;
            }

        }
        if($role->role_id === 3){

            foreach($use as $c){
                
                $template_code[]=$c->template;
            }

        }
        
        if(!empty($customer) && !empty($template)){       

            if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){                       

                $chan = DB::table('posted_audit_details')
                        ->select('posted_audit_details.*','posted_audits.*')
                        ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                        ->whereIn('posted_audits.channel_code',$template)
                        ->whereIn('posted_audits.customer_code',$customer)
                        ->whereIn('posted_audits.user_id',$user_id)
                        ->groupBy('posted_audit_details.category')
                        ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')
                    ->where('plano', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();     
            }
            if($role->role_id === 3){

                $chan = DB::table('posted_audit_details')
                        ->select('posted_audit_details.*','posted_audits.*')
                        ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                        ->whereIn('posted_audits.channel_code',$template)
                        ->whereIn('posted_audits.customer_code',$customer)
                        ->whereIn('posted_audits.template',$template_code)
                        ->groupBy('posted_audit_details.category')
                        ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')
                    ->where('plano', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();    
            }
        }

        if(!empty($customer) && empty($template)){

            if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

                $chan = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')                    
                    ->whereIn('posted_audits.user_id',$user_id)
                    ->whereIn('posted_audits.customer_code',$customer)
                    ->groupBy('posted_audit_details.category')
                    ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')                
                    ->where('plano', 1)
                    ->whereIn('category',$cat)                
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();
            }
            if($role->role_id === 3){

                $chan = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')                    
                    ->whereIn('posted_audits.template',$template_code)
                    ->whereIn('posted_audits.customer_code',$customer)
                    ->groupBy('posted_audit_details.category')
                    ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')                
                    ->where('plano', 1)
                    ->whereIn('category',$cat)                
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

            }
        }

        if(empty($customer) && !empty($template)){

            if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){                        

                $chan = DB::table('posted_audit_details')
                        ->select('posted_audit_details.*','posted_audits.*')
                        ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                        ->whereIn('posted_audits.channel_code',$template)                    
                        ->whereIn('posted_audits.user_id',$user_id)
                        ->groupBy('posted_audit_details.category')
                        ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')
                    ->where('plano', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();
            }

            if($role->role_id === 3){                    

                $chan = DB::table('posted_audit_details')
                        ->select('posted_audit_details.*','posted_audits.*')
                        ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                        ->whereIn('posted_audits.channel_code',$template)                    
                        ->whereIn('posted_audits.template',$template_code)
                        ->groupBy('posted_audit_details.category')
                        ->get();

                $cat =[];

                foreach($chan as $cid){

                    $cat[]= $cid->category;
                }         

                return self::select('category', 'category')
                    ->where('plano', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

            }


        }

        if(empty($customer) && empty($template)){

            $category_id = [];                

            $chan = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')                    
                    ->whereIn('posted_audits.user_id',$user_id)
                    ->groupBy('posted_audit_details.category')
                    ->get();

            $cat =[];

            foreach($chan as $cid){

                $cat[]= $cid->category;
            }         

            return self::select('category', 'category')
                ->where('plano', 1)
                ->whereIn('category',$cat)
                ->groupBy('category')
                ->orderBy('category')
                ->get();

        }
        
    }

    public static function SosCatFilter($customer,$template,$user,$store,$use){

        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);

        $user_id =[];

        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

            foreach($use as $c){
                $user_id[]=$c->user_id;
            }        

            $userd = DB::table('posted_audits')
                ->select('posted_audits.*')
                ->whereIn('user_id',$user_id)
                ->get();

            $audid = [];

            foreach($userd as $ud){
                $audid[]=$ud->id;
            }      

        }

        if($role->role_id === 3){

            foreach($use as $c){
                $user_id[]=$c->template;
            }        

            $userd = DB::table('posted_audits')
                ->select('posted_audits.*')
                ->whereIn('template',$user_id)
                ->get();

            $audid = [];

            foreach($userd as $ud){
                $audid[]=$ud->id;
            }      

        }

        if(!empty($customer) && !empty($template) && !empty($user) && !empty($store)){
        
                $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)
                    ->whereIn('posted_audits.customer_code',$customer)
                    ->whereIn('posted_audits.channel_code',$template)
                    ->whereIn('posted_audits.user_id',$user)
                    ->whereIn('posted_audits.store_code',$store)
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();
                       
        }
        if(!empty($customer) && !empty($template) && !empty($user) && empty($store)){

            $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)
                    ->whereIn('posted_audits.customer_code',$customer)
                    ->whereIn('posted_audits.channel_code',$template)
                    ->whereIn('posted_audits.user_id',$user)                    
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

        }        
        if(!empty($customer) && !empty($template) && empty($user) && empty($store)){

            $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)
                    ->whereIn('posted_audits.customer_code',$customer)
                    ->whereIn('posted_audits.channel_code',$template)                                    
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

        }
        if(!empty($customer) && empty($template) && empty($user) && empty($store)){

            $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)
                    ->whereIn('posted_audits.customer_code',$customer)              
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

        }
        if(empty($customer) && empty($template) && empty($user) && empty($store)){

            $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)    
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

        }
        if(empty($customer) && !empty($template) && !empty($user) && !empty($store)){

            $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)                    
                    ->whereIn('posted_audits.channel_code',$template)
                    ->whereIn('posted_audits.user_id',$user)
                    ->whereIn('posted_audits.store_code',$store)
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

        }
        if(empty($customer) && empty($template) && !empty($user) && !empty($store)){

            $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)
                    ->whereIn('posted_audits.user_id',$user)
                    ->whereIn('posted_audits.store_code',$store)
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

        }
        if(empty($customer) && empty($template) && empty($user) && !empty($store)){

            $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)
                    ->whereIn('posted_audits.store_code',$store)
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

        }

        if(empty($customer) && !empty($template) && empty($user) && !empty($store)){

            $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)
                    ->whereIn('posted_audits.channel_code',$template)                    
                    ->whereIn('posted_audits.store_code',$store)
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

        }
        if(!empty($customer) && empty($template) && empty($user) && !empty($store)){

            $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)
                    ->whereIn('posted_audits.customer_code',$customer)
                    ->whereIn('posted_audits.store_code',$store)
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

        }
        if(empty($customer) && !empty($template) && !empty($user) && empty($store)){

            $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)                    
                    ->whereIn('posted_audits.channel_code',$template)
                    ->whereIn('posted_audits.user_id',$user)                    
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

        }
        if(empty($customer) && !empty($template) && empty($user) && empty($store)){

            $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)
                    ->whereIn('posted_audits.channel_code',$template)
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

        }        
        if(empty($customer) && empty($template) && !empty($user) && empty($store)){

             $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)
                    ->whereIn('posted_audits.user_id',$user)    
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();
        }
        if(!empty($customer) && empty($template) && !empty($user) && empty($store)){

            $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)
                    ->whereIn('posted_audits.customer_code',$customer)
                    ->whereIn('posted_audits.user_id',$user)    
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

        }
        if(!empty($customer) && empty($template) && !empty($user) && !empty($store)){

            $catid = DB::table('posted_audit_details')
                    ->select('posted_audit_details.*','posted_audits.*')
                    ->join('posted_audits','posted_audits.id','=','posted_audit_details.posted_audit_id')
                    ->whereIn('posted_audit_details.posted_audit_id',$audid)
                    ->whereIn('posted_audits.customer_code',$customer)
                    ->whereIn('posted_audits.user_id',$user)    
                    ->whereIn('posted_audits.store_code',$store)
                    ->groupBy('category')
                    ->get();

                $cat =[];        

                foreach($catid as $cid){
                    $cat[]= $cid->category;
                }        

                return self::select('category', 'category')
                    ->where('sos', 1)
                    ->whereIn('category',$cat)
                    ->groupBy('category')
                    ->orderBy('category')
                    ->get();

        }
        
    }

}   
