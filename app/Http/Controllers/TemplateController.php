<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Template;
use Session;
class TemplateController extends Controller
{
   public function index(){

   	$templates = Template::all();
   	return view('template.index',compact('templates'));
   
   }

   public function create(){
   	
   	return view('template.create');
   
   }

   public function store(Request $request){

   	$this->validate($request, [
            'code' => 'required',
            'description' =>'required|unique:templates'                                 
        ]);

   	$template = new Template();
   	$template->code = $request->code;
   	$template->description = $request->description;
   	$template->active = 1;
   	$template->save();

   	Session::flash('flash_message', 'Template successfully added.');
    Session::flash('flash_class', 'alert-success');
    return redirect()->route("templatemaintenance.index");

   }

   public function edit($id){

   	$template = Template::where('id',$id)->first();

   	return view('template.edit',compact('template'));

   }

   public function update(Request $request,$id){
   	$template = Template::findOrFail($id);
   	$template->code = $request->code;
   	$template->description = $request->description;
   	$template->update();
   	Session::flash('flash_message', 'Template was successfully Updated.');
    Session::flash('flash_class', 'alert-success');
    return redirect('templatemaintenance');
   }

   public function updatestatus($id){

        $template = Template::findOrFail($id);
        
        if($template->active === 1){
                $template->active = 0;
                $template->update();
                Session::flash('flash_message', 'Template was successfully Deactivated.');
                Session::flash('flash_class', 'alert-success');
                return redirect('templatemaintenance');
            }
        elseif($template->active === 0){
                $template->active = 1;     
                $template->update();
                Session::flash('flash_message', 'Template was successfully Activated.');
                Session::flash('flash_class', 'alert-success');
                return redirect('templatemaintenance');
            }          
    }
}
