<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Audit;
use App\FormCategory;

class AuditCategoryController extends Controller
{
    public function index($id){
    	$audit = Audit::findOrFail($id);
    	$categories = FormCategory::where('audit_id',$audit->id)->get();
    	return view('auditcategory.index', compact('audit', 'categories'));
    }
}
