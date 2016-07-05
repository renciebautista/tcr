<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Template;
use Session;
class TemplateController extends Controller
{
   public function index(){

   	return view('template.index');
   
   }

   public function create(){
   	
   	return view('template.create');
   
   }

   public function store(Request $request){

   	$this->validate($request, [
            'code' => 'required|unique:templates',
            'description' =>'required'                                 
        ]);

   	$template = new Template();
   	$template->code = $request->code;
   	$template->description = $request->description;
   	$template->active = $request->active;
   	$template->save();

   	Session::flash('flash_message', 'Template successfully added.');
    Session::flash('flash_class', 'alert-success');

    return redirect()->route("templatemaintenance.index");

   }
}
