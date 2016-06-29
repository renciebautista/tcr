<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class AuditSecondaryDisplay extends Model
{	
	protected $fillable = ['audit_id','form_category_id', 'brand', 'customer'];
    public $timestamps = false;

    public static function createSecondaryDisplay($audit, $file_path){
    	 \DB::beginTransaction();
        try {
        	set_time_limit(0);
			ini_set('memory_limit', -1);
        	$sheetNames = \Excel::load($file_path)->getSheetNames();
    	 	\Excel::selectSheets($sheetNames[0])->load($file_path, function($reader) use($sheetNames,$audit) {
    	 		$customer_name = $sheetNames[0];
    	 		// dd($customer_name);
    	 		AuditSecondaryDisplayLookup::where('audit_id',$audit->id)
						->where('customer', $customer_name)
						->delete();
				AuditSecondaryDisplay::where('audit_id',$audit->id)
	    				->where('customer', $customer_name)
	    				->delete();
	    		$reader->noHeading();
	    		$records = $reader->toArray();

	    		$cnt = 0;
	    		// dd($records);
	    		$categories = [];
	    		foreach ($records as $row) {
	    			
	    			if($cnt == 0){
			        	foreach ($row as $value) {

				        	$header_field[] = $value;

				        	if($value != ''){
				        		$category = FormCategory::where('audit_id', $audit->id)
				        			->where('category', $value)
				        			->first();
				        		if(!empty($category)){
				        			$category->update(['second_display' => 1]);
				        		}else{
				        			$category = FormCategory::firstOrCreate(['audit_id' => $audit->id, 'category' => $value, 'second_display' => 1]);
				        		}
				        	}
			        	}
			        	$categories = FormCategory::where('audit_id', $audit->id)
			        		->whereIn('category', $header_field)
			        		->get();
			        }elseif($cnt == 1){
			        	$new_brand =[];
			        	for ($i=3; $i < count($row); $i++) { 
			        		dd($header_field);
			    //     		$category = $categories->filter(function($record) use ($header_field,$i){
							//    if( $record->category ==  $header_field[$i]) return $record;
							// })->first();
							$category = FormCategory::where('audit_id', $audit->id)
				        			->where('category', $header_field[$i])
				        			->first();

			        		if(!empty($category)){

			        			$brand = self::create(['audit_id' => $audit->id, 
			        				'form_category_id' => $category->id, 
			        				'customer' => $customer_name,
			        				'brand' => $row[$i]]);
			        			$brand_ids[$i] = $brand->id;
			        		}else{
			        			dd($header_field[$i]);
			        		}
			        	}
			        	dd($new_brand);
			        }else{
			        	// dd($brand_ids);	
			        	$store = AuditStore::where('audit_id',$audit->id)
			        		->where('store_code',trim($row[1]))->first();
			        	if(!empty($store)){
			        		for ($i=3; $i < count($row); $i++) { 
			        			if(($row[$i] == 1) || ($row[$i] == "1.0")){
			        				AuditSecondaryDisplayLookup::create(['audit_id' => $audit->id, 
			        					'customer' => $customer_name,
			        					'audit_store_id' => $store->id, 
			        					'secondary_display_id' => $brand_ids[$i]]);
			        			}
				        	}
			        	}
			        }
			        
			        $cnt++;
	    		}
    	 	});
            \DB::commit();
        } catch (\Exception $e) {
            dd($e);
            \DB::rollback();
        }
    }

}
