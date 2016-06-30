<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

use App\AuditOsaLookupDetail;
use App\AuditStore;

class AuditOsaLookup extends Model
{
    protected $fillable = ['audit_id', 'customer_code', 'region_code', 'distributor_code', 'store_code', 'channel_code'];
   	public $timestamps = false;

   	public function categories(){
        return $this->hasMany('App\AuditOsaLookupDetail','audit_osa_lookup_id', 'id');
    }

    public function customer(){
    	if($this->customer_code == '0'){
    		return 'ALL';
    	}else{
    		$customer = AuditStore::where('audit_id', $this->audit_id)
    			->where('customer_code', $this->customer_code)
    			->first();
    		return $customer->customer;
    	}
    }

    public function region(){
    	if($this->region_code == '0'){
    		return 'ALL';
    	}else{
    		$region = AuditStore::where('audit_id', $this->audit_id)
    			->where('region_code', $this->region_code)
    			->first();
    		return $region->region;
    	}
    }

    public function distributor(){
    	if($this->distributor_code == '0'){
    		return 'ALL';
    	}else{
    		$distributor = AuditStore::where('audit_id', $this->audit_id)
    			->where('distributor_code', $this->distributor_code)
    			->first();
    		return $distributor->distributor;
    	}
    }

    public function channel(){
    	if($this->channel_code == '0'){
    		return 'ALL';
    	}else{
    		$template = AuditStore::where('audit_id', $this->audit_id)
    			->where('channel_code', $this->channel_code)
    			->first();
            if(empty($template)){
                return $this->channel_code;
            }
    		return $template->template;
    	}
    }

    public function store(){
    	if($this->store_code == '0'){
    		return 'ALL';
    	}else{
    		$store = AuditStore::where('audit_id', $this->audit_id)
    			->where('store_code', $this->store_code)
    			->first();
    		return $store->store_name;
    	}
    }

   	public static function getOsaLookupsByAudit($audit){
   		return self::where('audit_id',$audit->id)->orderBy('id','desc')->get();
   	}

   	public static function createOsaLookup($audit,$file_path){
   		// AuditOsaLookupDetail::where('audit_id',$audit->id)->delete();
    	// self::where('audit_id',$audit->id)->delete();
    	
        \DB::beginTransaction();
        try {
        	$reader = ReaderFactory::create(Type::XLSX); // for XLSX files
    		$reader->open($file_path);
    		$header_field = [];
    		$brand_ids = [];

            foreach ($reader->getSheetIterator() as $sheet) {
                if($sheet->getName() == 'Sheet1'){
                    $cnt = 0;
                    foreach ($sheet->getRowIterator() as $row) {
                        if(!empty($row[0])){
                            if($cnt > 0){
                                $customer_code = 0;
                                $region_code = 0;
                                $distributor_code = 0;
                                $store_code = 0;
                                $channel_code = 0;
                                if(strtoupper($row[0]) != "ALL"){
                                    $customer_code = $row[0];
                                }
                                if(strtoupper($row[1]) != "ALL"){
                                    $region_code = $row[1];
                                }
                                if(strtoupper($row[2]) != "ALL"){
                                    $distributor_code = $row[2];
                                }
                                if(strtoupper($row[3]) != "ALL"){
                                    $store_code = $row[3];
                                }
                                if(strtoupper($row[4]) != "ALL"){
                                    $channel_code = $row[4];
                                }
                                $osa_lookup = self::firstOrCreate(['audit_id' => $audit->id, 
                                    'customer_code' => $customer_code, 
                                    'region_code' => $region_code, 
                                    'distributor_code' => $distributor_code, 
                                    'store_code' => $store_code, 
                                    'channel_code' => $channel_code
                                    ]);

                                AuditOsaLookupDetail::where('audit_osa_lookup_id',$osa_lookup->id)->delete();
                            }
                            $cnt++;
                        }
                        
                    }
                }
                
            }

    		foreach ($reader->getSheetIterator() as $sheet) {
    			if($sheet->getName() == 'Sheet1'){
    				$cnt = 0;
    				foreach ($sheet->getRowIterator() as $row) {
                        if(!empty($row[0])){
                            if($cnt > 0){
                                $customer_code = 0;
                                $region_code = 0;
                                $distributor_code = 0;
                                $store_code = 0;
                                $channel_code = 0;
                                if(strtoupper($row[0]) != "ALL"){
                                    $customer_code = $row[0];
                                }
                                if(strtoupper($row[1]) != "ALL"){
                                    $region_code = $row[1];
                                }
                                if(strtoupper($row[2]) != "ALL"){
                                    $distributor_code = $row[2];
                                }
                                if(strtoupper($row[3]) != "ALL"){
                                    $store_code = $row[3];
                                }
                                if(strtoupper($row[4]) != "ALL"){
                                    $channel_code = $row[4];
                                }
                                $osa_lookup = self::where('audit_id',$audit->id)
                                    ->where('customer_code',$customer_code)
                                    ->where('region_code',$region_code)
                                    ->where('distributor_code',$distributor_code) 
                                    ->where('store_code',$store_code)
                                    ->where('channel_code',$channel_code)
                                    ->first();
                                
                                $form_category = FormCategory::where('audit_id',$audit->id)->where('category', $row[5])->first();
                                if(!empty($form_category)){
                                    $form_category->osa = 1;
                                    $form_category->update();
                                    AuditOsaLookupDetail::create(['audit_id' => $audit->id,
                                        'audit_osa_lookup_id' => $osa_lookup->id,
                                        'form_category_id' => $form_category->id, 'target' => $row[8]]);
                                }
                            }
                            $cnt++;
                        }
    					
    			    }
    			}
    		    
    		}
             \DB::commit();
    		$reader->close();
        } catch (\Exception $e) {
            dd($e);
            \DB::rollback();
        }
   	}

    public static function getOsaCategory($id){
        $store = AuditStore::find($id);
        // dd($store);
        // store level
        $template = self::where('store_code',$store->store_code)->where('audit_id', $store->audit_id)->first(); 
        if(!empty($template)){ 
            return $template; //0001
        }

        $template = self::where('customer_code',$store->customer_code)->where('audit_id', $store->audit_id)->get();
        if(count($template) > 0){

            $template = self::where('customer_code',$store->customer_code)
                ->where('region_code',$store->region_code)
                ->where('audit_id', $store->audit_id)
                ->get();
            if(count($template) > 0){

                $template = self::where('customer_code',$store->customer_code)
                    ->where('region_code',$store->region_code)
                    ->where('distributor_code',$store->distributor_code)
                    ->where('audit_id', $store->audit_id)
                    ->get();
                if(count($template) > 0){
                    $template = self::where('customer_code',$store->customer_code)
                        ->where('region_code',$store->region_code)
                        ->where('distributor_code',$store->distributor_code)
                        ->where('channel_code',$store->channel_code)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                    if(!empty($template)){ 
                        return $template; //1111
                    }else{
                        return self::where('customer_code',$store->customer_code) //1110
                        ->where('region_code',$store->region_code)
                        ->where('distributor_code',$store->distributor_code)
                        ->where('channel_code',0)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                    }
                }else{
                    $template = self::where('customer_code',$store->customer_code)
                        ->where('region_code',$store->region_code)
                        ->where('distributor_code',0)
                        ->where('channel_code',$store->channel_code)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                    if(!empty($template)){ 
                        return $template; //1101
                    }else{
                        return self::where('customer_code',$store->customer_code) //1100
                        ->where('region_code',$store->region_code)
                        ->where('distributor_code',0)
                        ->where('channel_code',0)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                    }
                }
            }else{
                $template = self::where('customer_code',$store->customer_code)
                    ->where('region_code',0)
                    ->where('distributor_code',$store->distributor_code)
                    ->where('audit_id', $store->audit_id)
                    ->get();
                if(count($template) > 0){
                    $template = self::where('customer_code',$store->customer_code)
                        ->where('region_code',0)
                        ->where('distributor_code',$store->distributor_code)
                        ->where('channel_code',$store->channel_code)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                    if(!empty($template)){ 
                        return $template; //1011
                    }else{
                        return self::where('customer_code',$store->customer_code) //1010
                        ->where('region_code',0)
                        ->where('distributor_code',$store->distributor_code)
                        ->where('channel_code',0)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                    }
                }else{
                    $template = self::where('customer_code',$store->customer_code)
                        ->where('region_code',0)
                        ->where('distributor_code',0)
                        ->where('channel_code',$store->channel_code)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                     if(!empty($template)){ 
                        return $template; //1001
                    }else{
                        return self::where('customer_code',$store->customer_code) //1000
                        ->where('region_code',0)
                        ->where('distributor_code',0)
                        ->where('channel_code',0)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                    }
                }
            }
        }else{
            $template = self::where('customer_code',0)
                ->where('region_code',$store->region_code)
                ->where('audit_id', $store->audit_id)
                ->get();
            if(count($template) > 0){
                $template = self::where('customer_code',0)
                    ->where('region_code',$store->region_code)
                    ->where('distributor_code',$store->distributor_code)
                    ->where('audit_id', $store->audit_id)
                    ->get();
                if(count($template) > 0){
                    $template = self::where('customer_code',0) 
                        ->where('region_code',$store->region_code)
                        ->where('distributor_code',$store->distributor_code)
                        ->where('channel_code',$store->channel_code)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                     if(!empty($template)){ 
                        return $template; //0111
                    }else{
                        return self::where('customer_code',0) // 0110
                        ->where('region_code',$store->region_code)
                        ->where('distributor_code',$store->distributor_code)
                        ->where('channel_code',0)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                    }
                }else{
                    $template = self::where('customer_code',0) 
                        ->where('region_code',$store->region_code)
                        ->where('distributor_code',0)
                        ->where('channel_code',$store->channel_code)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                    if(!empty($template)){ 
                        return $template; //0101
                    }else{
                        return self::where('customer_code',0) // 0100
                        ->where('region_code',$store->region_code)
                        ->where('distributor_code',0)
                        ->where('channel_code',0)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                    }
                }
            }else{
                $template = self::where('customer_code',0)
                    ->where('region_code',0)
                    ->where('distributor_code',$store->distributor_code)
                    ->where('audit_id', $store->audit_id)
                    ->get();
                if(count($template) > 0){
                    $template = self::where('customer_code',0)
                        ->where('region_code',0)
                        ->where('distributor_code',$store->distributor_code)
                        ->where('channel_code',$store->channel_code)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                     if(!empty($template)){ 
                        return $template; // 0011
                    }else{
                        return self::where('customer_code',0) //0010
                        ->where('region_code',0)
                        ->where('distributor_code',$store->distributor_code)
                        ->where('channel_code',0)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                    }
                }else{
                    $template = self::where('customer_code',0)
                        ->where('region_code',0)
                        ->where('distributor_code',0)
                        ->where('channel_code',$store->channel_code)
                        ->where('audit_id', $store->audit_id)
                        ->first();
                     if(!empty($template)){ 
                        return $template; // 0001
                    }else{
                        return self::where('customer_code',0) // 0000
                            ->where('region_code',0)
                            ->where('distributor_code',0)
                            ->where('channel_code',0)
                            ->where('store_code',0)
                            ->where('audit_id', $store->audit_id)
                            ->first();
                    }
                }
            }
            
        }
    }
}
