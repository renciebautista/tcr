<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\UserSummary;
use App\Audit;
use App\User;
use App\PostedAudit;

class ReportController extends Controller
{
    public function getUserSummary($audit_id,$user_id){

    	$audit = Audit::findOrFail($audit_id);
        $user = User::findOrFail($user_id);

        $posted_audit = PostedAudit::where('user_id',$user_id)
            ->where('audit_id',$audit->id)->get();

        if($posted_audit->count() > 1){
            $usersummary = UserSummary::getSummary($audit,$user);

        	$detail = $usersummary->detail;

        	return json_encode((array)$detail);

        }else{
            $data['msg'] = "No Report Available";
            return json_encode($data);
        }
    }

    public function getStoreSummary($audit_id,$user_id){
        
    	$audit = Audit::findOrFail($audit_id);
        $user = User::findOrFail($user_id);

        $posted_audit = PostedAudit::where('user_id',$user_id)
            ->where('audit_id',$audit->id)->get();

        if($posted_audit->count() > 1){
            $usersummary = UserSummary::getSummary($audit,$user);

            $stores = $usersummary->stores;
            $stores->msg_status = 1;

            return $stores->toJson();
        }else{
            $data['msg'] = "No Report Available";
            return json_encode($data);
        }
       
    }
}
