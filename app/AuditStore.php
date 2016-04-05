<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Audit;
use App\EnrollmentType;

class AuditStore extends Model
{
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
				AuditEnrollmentTypeMapping::where('audit_id',$id)->delete();
				self::where('audit_id',$id)->delete();

				$records->each(function($row) use ($id)  {
					if(!is_null($row->account)){

						$user = User::where('username',$row->username)->first();
						if(empty($user)){
							$user = User::create(['name' => $row->fullname, 'username' => $row->username, 'password' => \Hash::make($row->username)]);
						}

						$enrollment_type = EnrollmentType::where('enrollmenttype',$row->enrollment_type)->first();
						if(empty($enrollment_type)){
							$enrollment_type = EnrollmentType::create(['enrollmenttype' => $row->enrollment_type, 'value' => 0]);
						}

						$audit_enrollment_mapping = AuditEnrollmentTypeMapping::where('audit_id',$id)->where('enrollment_type_id',$enrollment_type->id)->first();
						if(empty($audit_enrollment_mapping)){
							$audit_enrollment_mapping = AuditEnrollmentTypeMapping::create(['audit_id' => $id, 'enrollment_type_id' => $enrollment_type->id, 'value' => $enrollment_type->value]);
						}

						$store = new AuditStore;
						$store->audit_id = $id;
						$store->account = $row->account;
						$store->customer_code = $row->customer_code;
						$store->customer = $row->customer;
						$store->area = $row->area;
						$store->region_code = $row->region_code;
						$store->region = $row->region;
						$store->remarks = $row->remarks;
						$store->distributor_code = $row->distributor_code;
						$store->distributor = $row->distributor;
						$store->store_code = $row->store_code;
						$store->store_name = $row->store_name;
						$store->audit_enrollment_type_mapping_id = $audit_enrollment_mapping->id;
						$store->channel_code = $row->channel_code;
						$store->template = $row->template;
						$store->agency_code = $row->agency_code;
						$store->agency_description = $row->agency_description;
						$store->user_id = $user->id;
						$store->save();


				}
				
			});
			\DB::commit();
		} catch (\Exception $e) {
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
    		->groupBy('channel_code')
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
}
