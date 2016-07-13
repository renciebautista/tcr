<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Input;
use Excel;
use DB;
use App\User;
use App\ManagerFields;
class ImportUserMappingController extends Controller
{
    public function index(){
    	return view('import.user');
    }
    public function store(Request $request){

    	if(!$request->hasFile('file')){
    		Session::flash('flash_message', 'Error uploading.');
			Session::flash('flash_class', 'alert-danger');
			return redirect('import_user_mapping');
    	}
    	if($request->hasFile('file')){    	

    		$destinationPath = storage_path().'/uploads/user_mapping/';
			$fileName = $request->file('file')->getClientOriginalName();

			$request->file('file')->move($destinationPath, $fileName);

			$filePath = storage_path().'/uploads/user_mapping/' . $fileName;

			// $path = Input::file('file')->getRealPath();			
			$data = Excel::load($filePath, function($reader) {
			})->get();
			if(!empty($data) && $data->count()){
				foreach ($data as $key => $value) {

					$name = $value->name;
					$user_id = User::getUsername($name);					
					if(!empty($user_id)){
						$mapped_name = $value->mapped_name;
						$mapped_id = User::getMappedname($mapped_name);
						if(!empty($mapped_id)){						
							$map_fields = ManagerFields::Insert([
								'managers_id' =>$user_id->id,
								'fields_id' =>$mapped_id->id,
								]);
						}
					}										
				}
				Session::flash('flash_message', 'Uploading Completed.');
				Session::flash('flash_class', 'alert-success');
				return redirect('import_user_mapping');
			}
    	}
    }
}
