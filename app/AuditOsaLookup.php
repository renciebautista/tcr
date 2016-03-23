<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;


class AuditOsaLookup extends Model
{
    protected $fillable = ['audit_id', 'customer_code', 'region_code', 'distributor_code', 'store_code', 'channel_code'];
   	public $timestamps = false;

   	public static function getOsaLookupsByAudit($audit){
   		return self::where('audit_id',$audit->id)->get();
   	}

   	public static function createOsaLookup($audit,$file_path){
   		// AuditSecondaryDisplayLookup::where('audit_id',$audit->id)->delete();
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
						self::firstOrCreate(['audit_id' => $audit->id, 'customer_code' => $row[0], 'region_code' => $row[1], 'distributor_code' => $row[2], 'store_code' => $row[3], 'channel_code' => $row[4]]);
					}
					$cnt++;
			    }
			}
		    
		}

		$reader->close();
   	}
}
