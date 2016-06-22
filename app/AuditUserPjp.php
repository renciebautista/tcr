<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;


class AuditUserPjp extends Model
{
    public $timestamps = false;

    protected $fillable = ['audit_id', 'user_id', 'target'];

    public static function import($id,$file_path){
        \DB::beginTransaction();
        try {
        	self::where('audit_id',$id)->delete();
        	$reader = ReaderFactory::create(Type::XLSX); // for XLSX files
    		$reader->open($file_path);
    		$header_field = [];
    		$brand_ids = [];
            $sos_id = 0;
    		foreach ($reader->getSheetIterator() as $sheet) {
    			if($sheet->getName() == 'Sheet1'){
    				$cnt = 0;
    				foreach ($sheet->getRowIterator() as $row) {

    					if($cnt > 0){
                            if($row[0] != ''){
                                $user = User::where('name',$row[0])->first();
                                if(!empty($user)){
                                	self::firstOrCreate(['audit_id' => $id, 'user_id' => $user->id, 'target' => $row[1]]);
                                }
                            }
                            
    					}
    					$cnt++;
    			    }
    			}    		    
    		}

    		$reader->close();
            \DB::commit();
		} catch (\Exception $e) {
			dd($e);
			\DB::rollback();
		}
    }
}
