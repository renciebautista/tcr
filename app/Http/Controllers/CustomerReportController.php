<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;
use App\Audit;

class CustomerReportController extends Controller
{
    public function index(){
    	$customers = PostedAudit::getCustomers()->lists('customer','customer_code');
        $regions = PostedAudit::getRegions()->lists('region','region_code');
        $templates = PostedAudit::getTemplates()->lists('template','channel_code');
        $audits = PostedAudit::getAudits()->lists('description','audit_id');
        $pjps = ['1' => 'Within PJP', '2' => 'Outside PJP'];
        $customer_summaries = PostedAudit::getCustomerSummary();
    	return view('customerreport.index', compact('customers','regions','templates', 'audits', 'customer_summaries','pjps'));
    }

    public function create(Request $request){
        $request->flash();
        $customers = PostedAudit::getCustomers()->lists('customer','customer_code');
        $regions = PostedAudit::getRegions()->lists('region','region_code');
        $templates = PostedAudit::getTemplates()->lists('template','channel_code');
        $audits = PostedAudit::getAudits()->lists('description','audit_id');
        $pjps = ['1' => 'With PJP', '2' => 'Without PJP'];
        $customer_summaries = PostedAudit::getCustomerSummary($request);
        

        return view('customerreport.index', compact('customers','regions','templates', 'audits', 'customer_summaries','pjps'));
    }

    public function show($customer_code,$region_code,$channel_code,$audit_id){
        $audit = Audit::findOrFail($audit_id);
        $data['customer_code'] = $customer_code;
        $data['region_code'] = $region_code;
        $data['channel_code'] = $channel_code;
        $data['audit_id'] = $audit_id;
        $posted_audits = PostedAudit::customerSearch($data);
        $customer = PostedAudit::getCustomer($customer_code,$audit_id);
        $region = PostedAudit::getRegion($region_code,$audit_id);
        $template = PostedAudit::getTemplate($channel_code,$audit_id);
        return view('customerreport.show',compact('posted_audits', 'customer', 'region', 'template', 'audit'));
    }
}
