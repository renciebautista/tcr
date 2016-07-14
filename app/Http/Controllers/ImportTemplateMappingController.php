<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Input;
use Excel;
use DB;
use App\User;
use App\ManagerTemplates;
class ImportTemplateMappingController extends Controller
{
    public function index(){

    	return view('import.template');
    }

    public function store(Request $request){    	
    	
    if(!$request->hasFile('file')){
    		Session::flash('flash_message', 'Error uploading.');
			Session::flash('flash_class', 'alert-danger');
			return redirect('import_template_mapping');
    	}
    	if($request->hasFile('file')){    	

    		$destinationPath = storage_path().'/uploads/template_mapping/';
			$fileName = $request->file('file')->getClientOriginalName();

			$request->file('file')->move($destinationPath, $fileName);

			$filePath = storage_path().'/uploads/template_mapping/' . $fileName;
			
			$data = Excel::load($filePath, function($reader) {
			})->get();
			if(!empty($data) && $data->count()){
				foreach ($data as $key => $value) {

					$name = $value->name;
					$user_id = User::getUsername($name);			
					if(!empty($user_id)){

						$mapped_template = $value->templates;
						$mapped_id = ManagerTemplates::getMappedtemplate($mapped_template);						
						if(!empty($mapped_id)){							
							$map_tempalates = ManagerTemplates::Insert([
								'managers_id' =>$user_id->id,
								'templates_id' =>$mapped_id->id,
								]);
						}
					}										
				}
				Session::flash('flash_message', 'Uploading Completed.');
				Session::flash('flash_class', 'alert-success');
				return redirect('import_template_mapping');
			}
    	}
    }
}
