<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\FormCategory;
use App\FormGroup;
use App\PostedAudit;
use App\PostedAuditCategorySummary;

class StoreSummaryReportController extends Controller
{
    public function show($id){
    	$store = PostedAudit::findOrFail($id);
    	$data = PostedAuditCategorySummary::getCategorySummary($store->id);
    	$categories =  FormCategory::where('audit_id',$store->audit_id)->get();
    	$groups = FormGroup::where('audit_id',$store->audit_id)->get();
    	// dd($data);
    	return view('storesummary.show',compact('store', 'categories', 'groups', 'data'));
    }
}
