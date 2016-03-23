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
}
