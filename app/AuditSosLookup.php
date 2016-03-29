<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

use App\AuditSosLookupDetail;
use App\AuditStoreSos;
use App\AuditStore;

class AuditSosLookup extends Model
{
    protected $fillable = ['audit_id', 'customer_code', 'region_code', 'distributor_code', 'store_code', 'channel_code'];
   	public $timestamps = false;

    public function categories(){
        return $this->hasMany('App\AuditSosLookupDetail','audit_sos_lookup_id', 'id');
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
    

   	public static function getSosLookupsByAudit($audit){
   		return self::where('audit_id',$audit->id)->get();
   	}

   	public static function createSosLookup($audit,$file_path){
        AuditStoreSos::where('audit_id',$audit->id)->delete();
        AuditSosLookupDetail::where('audit_id',$audit->id)->delete();
    	self::where('audit_id',$audit->id)->delete();

    	$reader = ReaderFactory::create(Type::XLSX); // for XLSX files
		$reader->open($file_path);
		$header_field = [];
		$brand_ids = [];
		foreach ($reader->getSheetIterator() as $sheet) {
			if($sheet->getName() == 'Sheet1'){
				$cnt = 0;
				foreach ($sheet->getRowIterator() as $row) {

					if($cnt > 0){
                        if($row[0] != ''){
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

                            $sos_lookup = self::firstOrCreate(['audit_id' => $audit->id, 
                                'customer_code' => $customer_code, 
                                'region_code' => $region_code, 
                                'distributor_code' => $distributor_code, 
                                'store_code' => $store_code, 
                                'channel_code' => $channel_code]);

                            $form_category = FormCategory::where('audit_id',$audit->id)->where('category', $row[5])->first();
                            if(!empty($form_category)){
                                $form_category->sos = 1;
                                $form_category->update();

                                AuditSosLookupDetail::create(array('audit_id' => $audit->id, 
                                    'audit_sos_lookup_id' =>  $sos_lookup->id, 
                                    'form_category_id' =>  $form_category->id, 
                                    'sos_type_id' => 1, 
                                    'less' => 0.015, 
                                    'value' => $row[9])
                                );
                                AuditSosLookupDetail::create(array('audit_id' => $audit->id, 
                                    'audit_sos_lookup_id' =>  $sos_lookup->id, 
                                    'form_category_id' =>  $form_category->id, 
                                    'sos_type_id' => 2, 
                                    'less' => 0.015, 
                                    'value' => $row[10])
                                );
                            }
                        }
                        
					}
					$cnt++;
			    }
			}

            if($sheet->getName() == 'Sheet2'){
                $cnt = 0;
                foreach ($sheet->getRowIterator() as $row) {

                    if($cnt > 0){
                        if($row[0] != ''){
                            $store = AuditStore::where('audit_id',$audit->id)->where('store_code',$row[0])->first();
                            $form_category = FormCategory::where('audit_id',$audit->id)->where('category', $row[2])->first();
                            $sos = SosType::where('sos',strtoupper($row[3]))->first();
                            if((!empty($store)) && (!empty($form_category))){
                                AuditStoreSos::create(['audit_id' => $audit->id,
                                    'audit_store_id' => $store->id,
                                    'form_category_id' => $form_category->id,
                                    'sos_type_id' => $sos->id]);
                            }
                        }
                        
                    }
                    $cnt++;
                }
            }

		    
		}

		$reader->close();
   	}
}
