<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\Style\Color;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\User;
use App\Audit;
use App\PostedAudit;
use App\PostedAuditDetail;
use App\PostedAuditCategorySummary;
use App\DeviceError;
use App\CheckIn;

class UploadController extends Controller
{
    public function storeaudit(Request $request)
	{
		if ($request->hasFile('data'))
		{
		    $destinationPath = storage_path().'/uploads/audit/';
			$fileName = $request->file('data')->getClientOriginalName();

			$request->file('data')->move($destinationPath, $fileName);

			$filePath = storage_path().'/uploads/audit/' . $fileName;

			$filename_data = explode("_", $fileName);
			$user_id = $filename_data[0];
			$store_code = $filename_data[1];
		   
			DB::beginTransaction();
			try {
				
			    $reader = ReaderFactory::create(Type::CSV); // for XLSX files
			    $reader->setFieldDelimiter('|');
			    $reader->open($filePath);

			    $first_row = true;
			    $summary = false;
			    $audit_id = 0;
			    foreach ($reader->getSheetIterator() as $sheet) {
			        foreach ($sheet->getRowIterator() as $row) {
			            if($first_row){
			            	$user = User::find($row[0]);
			            	$audit = Audit::find($row[1]);

			            	$posted_audit = PostedAudit::where('user_id',$user->id)
			            		->where('audit_id',$audit->id)
			            		->where('store_code', $row[9])
			            		->first();

			            	if(!empty($posted_audit)){

				               	$posted_audit->user_id = $row[0];
				                $posted_audit->audit_id = $row[1];

				                $posted_audit->account = $row[2];
				                $posted_audit->customer_code = $row[3];
				                $posted_audit->customer = $row[4];
				                $posted_audit->region_code = $row[5];
				                $posted_audit->region = $row[6];
				                $posted_audit->distributor_code = $row[7];
				                $posted_audit->distributor = $row[8];

				                $posted_audit->store_code = $row[9];
				                $posted_audit->store_name = $row[10];
				                $posted_audit->channel_code = $row[11];
				                $posted_audit->template = $row[12];
				                $posted_audit->perfect_store = $row[13];
				                $posted_audit->osa = $row[14];
				                $posted_audit->npi = $row[15];
				                $posted_audit->planogram = $row[16];
				                if(isset($row[17])){
				                	$posted_audit->area = $row[17];
				                }
				               
				                $posted_audit->updated_at = date('Y-m-d H:i:s');

				                $posted_audit->update();
				                
			            	}else{
			            		$posted_audit = new PostedAudit;
			               
				               	$posted_audit->user_id = $row[0];
				                $posted_audit->audit_id = $row[1];

				                $posted_audit->account = $row[2];
				                $posted_audit->customer_code = $row[3];
				                $posted_audit->customer = $row[4];
				                $posted_audit->region_code = $row[5];
				                $posted_audit->region = $row[6];
				                $posted_audit->distributor_code = $row[7];
				                $posted_audit->distributor = $row[8];

				                $posted_audit->store_code = $row[9];
				                $posted_audit->store_name = $row[10];
				                $posted_audit->channel_code = $row[11];
				                $posted_audit->template = $row[12];
				                $posted_audit->perfect_store = $row[13];
				                $posted_audit->osa = $row[14];
				                $posted_audit->npi = $row[15];
				                $posted_audit->planogram = $row[16];

				                if(isset($row[17])){
				                	$posted_audit->area = $row[17];
				                }

				                $posted_audit->save();
			            	}
			            	$first_row = false;
			            }
			        }
			    }
			   
			    $reader->close();
			    DB::commit();
			   
			    return response()->json(array('msg' => 'file uploaded',  'status' => 0, 'audit_id' => $posted_audit->id));
				
			} catch (Exception $e) {
			    DB::rollback();
			    return response()->json(array('msg' => 'file uploaded error', 'status' => 1));
			}
		}
		return response()->json(array('msg' => 'file uploaded error', 'status' => 1));
		
	}

	public function uploaddetails(Request $request)
	{
		if ($request->hasFile('data'))
		{
		    $destinationPath = storage_path().'/uploads/audit/';
			$fileName = $request->file('data')->getClientOriginalName();

			$request->file('data')->move($destinationPath, $fileName);

			$filePath = storage_path().'/uploads/audit/' . $fileName;

			$filename_data = explode("_", $fileName);
			$user_id = $filename_data[0];
			$store_code = $filename_data[1];
		   
			DB::beginTransaction();
			try {
				
			    $reader = ReaderFactory::create(Type::CSV); // for XLSX files
			    $reader->setFieldDelimiter('|');
			    $reader->open($filePath);

			    $first_row = true;
			    $summary = false;
			    $audit_id = 0;
			    foreach ($reader->getSheetIterator() as $sheet) {
			        foreach ($sheet->getRowIterator() as $row) {
			            if($first_row){
			            	$user = User::find($row[0]);
			            	$audit = Audit::find($row[1]);

			            	$posted_audit = PostedAudit::where('user_id',$user->id)
			            		->where('audit_id',$audit->id)
			            		->where('store_code', $row[9])
			            		->first();

			            	if(!empty($posted_audit)){

				               	$posted_audit->user_id = $row[0];
				                $posted_audit->audit_id = $row[1];

				                $posted_audit->account = $row[2];
				                $posted_audit->customer_code = $row[3];
				                $posted_audit->customer = $row[4];
				                $posted_audit->region_code = $row[5];
				                $posted_audit->region = $row[6];
				                $posted_audit->distributor_code = $row[7];
				                $posted_audit->distributor = $row[8];

				                $posted_audit->store_code = $row[9];
				                $posted_audit->store_name = $row[10];
				                $posted_audit->channel_code = $row[11];
				                $posted_audit->template = $row[12];
				                $posted_audit->perfect_store = $row[13];
				                $posted_audit->osa = $row[14];
				                $posted_audit->npi = $row[15];
				                $posted_audit->planogram = $row[16];

				                $posted_audit->updated_at = date('Y-m-d H:i:s');

				                $posted_audit->update();

				                PostedAuditDetail::where('posted_audit_id',$posted_audit->id)->delete();
				                PostedAuditCategorySummary::where('posted_audit_id',$posted_audit->id)->delete();
				                
			            	}else{
			            		$posted_audit = new PostedAudit;
			               
				               	$posted_audit->user_id = $row[0];
				                $posted_audit->audit_id = $row[1];

				                $posted_audit->account = $row[2];
				                $posted_audit->customer_code = $row[3];
				                $posted_audit->customer = $row[4];
				                $posted_audit->region_code = $row[5];
				                $posted_audit->region = $row[6];
				                $posted_audit->distributor_code = $row[7];
				                $posted_audit->distributor = $row[8];

				                $posted_audit->store_code = $row[9];
				                $posted_audit->store_name = $row[10];
				                $posted_audit->channel_code = $row[11];
				                $posted_audit->template = $row[12];
				                $posted_audit->perfect_store = $row[13];
				                $posted_audit->osa = $row[14];
				                $posted_audit->npi = $row[15];
				                $posted_audit->planogram = $row[16];

				                $posted_audit->save();
			            	}
			            	$first_row = false;
			            }else{

			            	if($row[0] == 'audit_summary'){
			            		$summary = true;
			            		continue;
			            	}
			            	if(!$summary){
			            		PostedAuditDetail::insert([
				                    'posted_audit_id' => $posted_audit->id,
				                    'category' => $row[0],
				                    'group' => $row[1],
				                    'prompt' => $row[2],
				                    'type' => $row[3],
				                    'answer' => $row[4]]);
			            	}else{
			            		PostedAuditCategorySummary::insert([
				                    'posted_audit_id' => $posted_audit->id,
				                    'category' => $row[0],
				                    'group' => $row[1],
				                    'passed' => $row[2]]);
			            	}
			            	
			            }
			        }
			    }
			   
			    $reader->close();
			    DB::commit();
			   
			    return response()->json(array('msg' => 'file uploaded',  'status' => 0, 'audit_id' => $posted_audit->id));
				
			} catch (Exception $e) {
			    DB::rollback();
			    return response()->json(array('msg' => 'file uploaded error', 'status' => 1));
			}
		}
		return response()->json(array('msg' => 'file uploaded error', 'status' => 1));
		
	}

	public function uploadimage($audit_id, Request $request){
		if ($request->hasFile('data'))
		{
	        $destinationPath = storage_path().'/uploads/image/'.$audit_id."/";
	        $fileName = $request->file('data')->getClientOriginalName();
	        $request->file('data')->move($destinationPath, $fileName);

	        return response()->json(array('msg' => 'file uploaded', 'status' => 0, 'audit_id' => $audit_id));
	    }
	    return response()->json(array('msg' => 'file uploaded error', 'status' => 1));
    }

    public function uploadtrace(Request $request){
		if ($request->hasFile('data'))
		{
	        $destinationPath = storage_path().'/uploads/traces/';
	        $filename = $request->file('data')->getClientOriginalName();
	        $request->file('data')->move($destinationPath, $filename);

	        $error = DeviceError::where('filename',$filename)->first();
	        if(!empty($error)){
	        	$error->updated_at = date('Y-m-d H:i:s');
	        	$error->update();
	        }else{
	        	DeviceError::create(['filename' => $filename]);
	        }

	        return response()->json(array('msg' => 'Error trace successfully submitted.', 'status' => 0));
	    }
	    return response()->json(array('msg' => 'Failed in submitting error trace.', 'status' => 1));
    }

    public function uploadcheckin(Request $request){
    	if ($request->hasFile('data'))
		{
		    $destinationPath = storage_path().'/uploads/checkins/';
			$fileName = $request->file('data')->getClientOriginalName();

			$request->file('data')->move($destinationPath, $fileName);

			$filePath = storage_path().'/uploads/checkins/' . $fileName;
		   
			DB::beginTransaction();
			try {
				
			    $reader = ReaderFactory::create(Type::CSV); // for XLSX files
			    $reader->setFieldDelimiter('|');
			    $reader->open($filePath);

			    foreach ($reader->getSheetIterator() as $sheet) {
			        foreach ($sheet->getRowIterator() as $row) {
			        	$date_time = explode("-", $row[12]);
			        	$date = explode("/", $date_time[0]);
			            CheckIn::firstOrCreate([
			            	'user_id' => $row[0],
			            	'audit_id' => $row[1],
			            	'account' => $row[2],
			            	'customer_code' => $row[3],
			            	'customer' => $row[4],
			            	'area' => $row[5],
			            	'region_code' => $row[6],
			            	'region' => $row[7],
			            	'distributor_code' => $row[8],
			            	'distributor' => $row[9],
			            	'store_code' => $row[10],
			            	'store_name' => $row[11],
			            	'checkin' => $date[2].'-'.$date[0].'-'.$date[1].' '. $date_time[1],
			            	'lat' => $row[13],
			            	'long' => $row[14]]);

			        }
			    }
			   
			    $reader->close();
			    DB::commit();
			   
			    return response()->json(array('msg' => 'file uploaded',  'status' => 0));
				
			} catch (Exception $e) {
			    DB::rollback();
			    return response()->json(array('msg' => 'file uploaded error', 'status' => 1));
			}
		}
		return response()->json(array('msg' => 'file uploaded error', 'status' => 1));
    }
}
