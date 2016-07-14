<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Input;
use DB;
use Excel;
use App\Template;
class ImportMassTemplateController extends Controller
{
    public function index(){
    	return view('import.masstemplate');
    }

    public function store(Request $request){
		if(!$request->hasFile('file')){
    		Session::flash('flash_message', 'Error uploading.');
			Session::flash('flash_class', 'alert-danger');
			return redirect('import_masstemplate');
    	}
    	if($request->hasFile('file')){    	

    		$destinationPath = storage_path().'/uploads/masstemplate/';
			$fileName = $request->file('file')->getClientOriginalName();

			$request->file('file')->move($destinationPath, $fileName);

			$filePath = storage_path().'/uploads/masstemplate/' . $fileName;
			
			$data = Excel::load($filePath, function($reader) {
			})->get();			
			if(!empty($data) && $data->count()){
				foreach ($data as $key => $value) {

					$code = $value->code;													
					if(!empty($code)){						
						$description = $value->description;						
						if(!empty($description)){	

							$mass = Template::Insert([
								'code' =>$code,
								'description' =>$description,
								'active' => 1,
							]);
						}
					}										
				}
				Session::flash('flash_message', 'Uploading Completed.');
				Session::flash('flash_class', 'alert-success');
				return redirect('import_masstemplate');
			}
    	}
    }
}
