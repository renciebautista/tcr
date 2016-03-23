<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class AuditSecondaryDisplay extends Model
{	
	protected $fillable = ['audit_id','form_category_id', 'brand'];
    public $timestamps = false;

    public static function createSecondaryDisplay($audit, $file_path){
    	AuditSecondaryDisplayLookup::where('audit_id',$audit->id)->delete();
    	self::where('audit_id',$audit->id)->delete();

    	$reader = ReaderFactory::create(Type::XLSX); // for XLSX files
		$reader->open($file_path);
		$header_field = [];
		$brand_ids = [];
		foreach ($reader->getSheetIterator() as $sheet) {
			if($sheet->getName() == 'Sheet1'){
				$cnt = 0;
				foreach ($sheet->getRowIterator() as $row) {
			        if($cnt == 0){
			        	foreach ($row as $value) {
				        	$header_field[] = $value;
			        	}
			        }elseif($cnt == 1){
			        	for ($i=3; $i < count($row); $i++) { 
			        		$category = FormCategory::firstOrCreate(['audit_id' => $audit->id,
			        			'category' => $header_field[$i]]);
			        		if(!empty($category)){
			        			$brand = self::create(['audit_id' => $audit->id, 'form_category_id' => $category->id, 'brand' => $row[$i]]);
			        			$brand_ids[$i] = $brand->id;
			        		}
			        	}
			        }else{
			        	$store = AuditStore::where('audit_id',$audit->id)
			        		->where('store_code',trim($row[1]))->first();

			        	if(!empty($store)){
			        		for ($i=3; $i < count($row); $i++) { 
			        			if(($row[$i] == 1) || ($row == "1.0")){
			        				AuditSecondaryDisplayLookup::create(['audit_id' => $audit->id, 'audit_store_id' => $store->id, 'secondary_display_id' => $brand_ids[$i]]);
			        			}
				        		
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
