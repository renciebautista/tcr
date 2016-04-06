<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\FormCategory;
use App\FormGroup;
use App\PostedAudit;

class StoreSummaryReportController extends Controller
{
    public function show($id){
    	$store = PostedAudit::findOrFail($id);
    	$categories = FormCategory::where('audit_id',1)->get();
    	$groups = FormGroup::where('audit_id',1)->get();
    	return view('storesummary.show',compact('store', 'categories', 'groups'));
    }
}
