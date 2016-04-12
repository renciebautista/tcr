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

    public function store(Request $request,$id){
    	if($request->ajax()){
	        $value = $request->value;
	        $text = $request->name;
			preg_match('#\[(.*?)\]#', $text, $match);
			$id = $match[1];
			$field = explode("[", $text, 2);

			FormCategory::where('id',$id)->update([$field[0] => $value]);
	    }
    }
}
