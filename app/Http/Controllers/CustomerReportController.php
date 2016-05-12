<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;

class CustomerReportController extends Controller
{
    public function index(){
    	$customers = PostedAudit::getCustomers()->lists('customer','customer_code');
        $regions = PostedAudit::getRegions()->lists('region','region_code');
        $templates = PostedAudit::getTemplates()->lists('template','channel_code');
        $audits = PostedAudit::getAudits()->lists('description','audit_id');
        $customer_summaries = PostedAudit::getCustomerSummary();
    	return view('customerreport.index', compact('customers','regions','templates', 'audits', 'customer_summaries'));
    }

    public function create(Request $request){
        $request->flash();
        $customers = PostedAudit::getCustomers()->lists('customer','customer_code');
        $regions = PostedAudit::getRegions()->lists('region','region_code');
        $templates = PostedAudit::getTemplates()->lists('template','channel_code');
        $audits = PostedAudit::getAudits()->lists('description','audit_id');
        $customer_summaries = PostedAudit::getCustomerSummary($request);

        return view('customerreport.index', compact('customers','regions','templates', 'audits', 'customer_summaries'));
    }

    public function show($audit_id,$user_id){
     //    $audit = Audit::findOrFail($audit_id);
     //    $user = User::findOrFail($user_id);
     //    $usersummary = UserSummary::getSummary($audit,$user);

    	// $detail = $usersummary->detail;
    	// $stores = $usersummary->stores;

    	// return view('usersummaryreport.show', compact('detail', 'stores'));
    }
}
