<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AuditUser;
class AuditStore extends Model
{
	public function fielduser(){
		return $this->belongsTo('App\AuditUser', 'audit_users_id');
	}

    public static function import($id,$records){
    	\DB::beginTransaction();
			try {
				self::where('audit_id',$id)->delete();
				AuditUser::where('audit_id',$id)->delete();

				$records->each(function($row) use ($id)  {
					if(!is_null($row->account)){
						$audituser = AuditUser::where('audit_id', $id)
							->where('username',$row->username)->first();
						if(empty($audituser)){
							$audituser = AuditUser::create(['audit_id' => $id, 'username' => $row->username, 'password' => \Hash::make($row->username), 'fullname' => $row->fullname, 'email' => $row->email]);
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
						$store->enrollment_type = $row->enrollment_type;
						$store->channel_code = $row->channel_code;
						$store->template = $row->template;
						$store->agency_code = $row->agency_code;
						$store->agency_description = $row->agency_description;
						$store->audit_users_id = $audituser->id;
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
