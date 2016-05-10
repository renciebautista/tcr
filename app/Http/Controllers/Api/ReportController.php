<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\UserSummary;
use App\Audit;
use App\User;

class ReportController extends Controller
{
    public function getUserSummary($audit_id,$user_id){

    	$audit = Audit::findOrFail($audit_id);
        $user = User::findOrFail($user_id);
        $usersummary = UserSummary::getSummary($audit,$user);

    	$detail = $usersummary->detail;
    	// $stores = $usersummary->stores;

    	return json_encode((array)$detail);
    }

    public function getStoreSummary($audit_id,$user_id){

    	$audit = Audit::findOrFail($audit_id);
        $user = User::findOrFail($user_id);
        $usersummary = UserSummary::getSummary($audit,$user);

    	$stores = $usersummary->stores;

    	return $stores->toJson();
    }
}
