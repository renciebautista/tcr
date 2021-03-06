<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Audit;
use App\EnrollmentType;

class AuditStore extends Model
{
	public $fillable = ['audit_id', 'account', 'customer_code', 'customer', 'area', 'region_code', 'region',
		'remarks', 'distributor_code', 'distributor', 'store_code', 'store_name', 'audit_enrollment_type_mapping_id', 
		'channel_code', 'template', 'agency_code', 'agency_description', 'user_id'];
		
	public function fielduser(){
		return $this->belongsTo('App\User', 'user_id');
	}

	public function audit(){
		return $this->belongsTo('App\Audit','audit_id');
	}

	public function auditEnrollment(){
		return $this->belongsTo('App\AuditEnrollmentTypeMapping','audit_enrollment_type_mapping_id');
	}

	public static function getUserStores(User $user){
		$dt = date('Y-m-d');
		$audit = Audit::where('start_date','<=',$dt)
			->where('end_date','>=',$dt)
			->first();

		if(!empty($audit)){
			return self::where('user_id',$user->id)
				->where('audit_id',$audit->id)
				->get();
		}

		return null;
		
	}

    public static function import($id,$records){
    	\DB::beginTransaction();
			try {
				$invalid_stores = [];
				$records->each(function($row) use ($id)  {
					if(!is_null($row->account)){

						if(!empty($row->fullname)){
							if(empty($row->username)){
								$user = User::where('name',$row->fullname)->first();
								if(empty($user)){
									$last_user = User::orderBy('id', 'desc')->first();
									$last_id =  (int)substr($last_user->username, 4);
									$last_id++;
									$username = 'User'.$last_id;
									$user = User::create(['name' => strtoupper($row->fullname), 'username' => $username, 'password' => \Hash::make($username)]);
								}
							}else{
								$user = User::where('name',$row->fullname)->first();
								if(empty($user)){
									$username = $row->username;
									$user = User::create(['name' => strtoupper($row->fullname), 'username' => $username, 'password' => \Hash::make($username)]);
								}
							}
							
							$e_type = 'ENVISION';
							if($row->enrollment_type != ''){
								$e_type = $row->enrollment_type;
							}

							$enrollment_type = EnrollmentType::where('enrollmenttype',$e_type)->first();
							if(empty($enrollment_type)){
								$enrollment_type = EnrollmentType::create(['enrollmenttype' => $e_type, 'value' => 0]);
							}

							$audit_enrollment_mapping = AuditEnrollmentTypeMapping::where('audit_id',$id)->where('enrollment_type_id',$enrollment_type->id)->first();
							if(empty($audit_enrollment_mapping)){
								$audit_enrollment_mapping = AuditEnrollmentTypeMapping::create(['audit_id' => $id, 'enrollment_type_id' => $enrollment_type->id, 'value' => $enrollment_type->value]);
							}

							$store = self::where('audit_id',$id)
								->where('store_code',$row->store_code)
								->first();

							if(empty($store)){
								$store = self::firstOrCreate([
									'audit_id' => $id,
									'account' => $row->account,
									'customer_code' => $row->customer_code,
									'customer' => $row->customer,
									'area' => $row->area,
									'region_code' => $row->region_code,
									'region' => $row->region,
									'remarks' => $row->remarks,
									'distributor_code' => $row->distributor_code,
									'distributor' => $row->distributor,
									'store_code' => $row->store_code,
									'store_name' => $row->store_name,
									'audit_enrollment_type_mapping_id' => $audit_enrollment_mapping->id,
									'channel_code' => $row->channel_code,
									'template' => $row->template,
									'agency_code' => $row->agency_code,
									'agency_description' => $row->agency_description,
									'user_id' => $user->id
									]);
							}else{
								$invalid_stores[] = $row;
							}
						}
				}

				return $invalid_stores;
				
			});
			\DB::commit();
		} catch (\Exception $e) {
			dd($e);
			\DB::rollback();
		}
    }

    public static function createStore($audit,$file_path){
    	$invalid_stores = [];
    	 \DB::beginTransaction();
    	 try {
    	 	$sheetNames = \Excel::load($file_path)->getSheetNames();
    	 	\Excel::selectSheets($sheetNames[0])->load($file_path, function($reader) use ($sheetNames,$audit) {
    	 		$stores = AuditStore::where('customer',$sheetNames[0])
    	 			->where('audit_id',$audit->id)
    	 			->get();
    	 		$store_ids = [];
    	 		foreach ($stores as $store) {
    	 			$store_ids[] = $store->id;
    	 		}

    	 		AuditSecondaryDisplayLookup::whereIn('audit_store_id',$store_ids)->delete();
    	 		AuditStoreSos::whereIn('audit_store_id',$store_ids)->delete();
    	 		
    	 		AuditStore::where('customer',$sheetNames[0])
    	 			->where('audit_id',$audit->id)
    	 			->delete();

    	 		$results = $reader->get();
                foreach ($results as $key => $row) {
                	if(!is_null($row->account)){

						if(!empty($row->fullname)){
							$user = User::where('name',$row->fullname)->first();
							if(empty($user)){
								$validuser = false;
								$cnt = 1;
								while (!$validuser) {
									$username = 'User'.sprintf("%02d", $cnt);;
									$x = User::where('username',$username)->first();
									if(empty($x)){
										$validuser = true;
									}
									$cnt++;
								}
								
								$user = User::create(['name' => strtoupper($row->fullname), 'username' => $username, 'password' => \Hash::make($username)]);
								\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
								$user->roles()->attach(2);
								\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
							}
							
							$e_type = 'ENVISION';
							if($row->enrollment_type != ''){
								if(strtolower($row->enrollment_type) ==  strtolower('Non-envision')){
									$e_type = 'Non envision';
								}else{
									$e_type = $row->enrollment_type;
								}
								
							}
							

							$enrollment_type = EnrollmentType::where('enrollmenttype',$e_type)->first();
							if(empty($enrollment_type)){
								$enrollment_type = EnrollmentType::create(['enrollmenttype' => $e_type, 'value' => 0]);
							}

							$audit_enrollment_mapping = AuditEnrollmentTypeMapping::where('audit_id',$audit->id)->where('enrollment_type_id',$enrollment_type->id)->first();
							if(empty($audit_enrollment_mapping)){
								$audit_enrollment_mapping = AuditEnrollmentTypeMapping::create(['audit_id' => $audit->id, 'enrollment_type_id' => $enrollment_type->id, 'value' => $enrollment_type->value]);
							}

							$store = self::where('audit_id',$audit->id)
								->where('store_code',$row->store_code)
								->first();
							if((empty($store)) && (!empty($row->channel_code))){
								$store = self::firstOrCreate([
									'audit_id' => $audit->id,
									'account' => $row->account,
									'customer_code' => $row->customer_code,
									'customer' => $row->customer,
									'area' => $row->area,
									'region_code' => $row->region_code,
									'region' => $row->region,
									'remarks' => $row->remarks,
									'distributor_code' => $row->distributor_code,
									'distributor' => $row->distributor,
									'store_code' => $row->store_code,
									'store_name' => $row->store_name,
									'audit_enrollment_type_mapping_id' => $audit_enrollment_mapping->id,
									'channel_code' => $row->channel_code,
									'template' => $row->template,
									'agency_code' => $row->agency_code,
									'agency_description' => $row->agency_description,
									'user_id' => $user->id
									]);
							}else{
								$invalid_stores[] = $row;
							}

							
						}
					}
                }
    	 	});
			// dd($invalid_stores);
			return $invalid_stores;
    	 	 \DB::commit();
    	 } catch (Exception $e) {
    	 	dd($e);
            \DB::rollback();
    	 }
    }
    public static function getCustomerLists($audit){
    	return self::orderBy('customer')
    		->where('audit_id', $audit->id)
    		->groupBy('customer_code')
    		->orderBy('customer')
    		->lists('customer','customer_code')
    		->all();
    }

    public static function getRegionLists($audit){
    	return self::where('audit_id', $audit->id)
    		->groupBy('region_code')
    		->orderBy('region')
    		->lists('region','region_code')
    		->all();
    }

    public static function getDistributorLists($audit){
    	return self::where('audit_id', $audit->id)
    		->groupBy('distributor_code')
    		->orderBy('distributor')
    		->lists('distributor','distributor_code')
    		->all();
    }

    public static function getTemplateLists($audit){
    	return self::where('audit_id', $audit->id)
    		->groupBy('template')
    		->orderBy('template')
    		->lists('template','channel_code')
    		->all();
    }

    public static function getStoreLists($audit){
    	return self::where('audit_id', $audit->id)
    		->groupBy('store_code')
    		->orderBy('store_name')
    		->lists('store_name','store_code')
    		->all();
    }

    public static function search($request,$id){
    	return self::join('users', 'users.id', '=', 'audit_stores.user_id')
    		->where('audit_id',$id)
    		->where(function($query) use ($request){
                $query->where('customer', 'LIKE', "%$request->search%");
	            $query->orWhere('area', 'LIKE', "%$request->search%");
	            $query->orWhere('region', 'LIKE', "%$request->search%");
	            $query->orWhere('distributor', 'LIKE', "%$request->search%");
	            $query->orWhere('store_code', 'LIKE', "%$request->search%");
	            $query->orWhere('store_name', 'LIKE', "%$request->search%");
	            $query->orWhere('users.name', 'LIKE', "%$request->search%");
	            $query->orWhere('template', 'LIKE', "%$request->search%");
            })
            ->orderBy('audit_stores.id','desc')
            ->paginate(100)
            ->appends(['search' => $request->search]);
    }

    public static function exportStore(){

    	return self::select('audits.description','users.name','audit_stores.*')
    		->join('users','users.id','=','audit_stores.user_id')
    		->join('audits','audits.id','=','audit_stores.audit_id')
    		->orderBy('audits.id','asc')
    		->get();
    }

    public static function remapToNew($user_id,$new_user_id){

    	$old =  self::select('audit_stores.id','audit_stores.user_id','channel_code')
    		->where('user_id',$user_id)    		
    		->get();
    	
    	foreach($old as $o){
    		$o->user_id = $new_user_id;
    		$o->update();    	
    	}
    }
}
