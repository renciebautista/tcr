<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Http\Requests;
use App\Audit;
use App\AuditStore;
use App\UpdatedHash;

class AuditStoreController extends Controller
{
    public function index(Request $request, $id)
    {
        $request->flash();
        $audit = Audit::findOrFail($id);
        $stores = AuditStore::search($request,$id);
        return view('auditstore.index',compact('audit', 'stores'));
    }

    public function create($id){
    	$audit = Audit::findOrFail($id);
    	return view('auditstore.create',compact('audit'));
    }

    public function store(Request $request, $id){
        set_time_limit(0);
    	if ($request->hasFile('file')){
            $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());
            
            $audit = Audit::findOrFail($id);
            $invalid_stores = AuditStore::createStore($audit,$file_path);

            \Artisan::call('db:seed', ['--class' => 'RemapUserSeeder']);

            if (\File::exists($file_path))
            {
                \File::delete($file_path);
            }


            $hash = UpdatedHash::find(1);
            if(empty($hash)){
                UpdatedHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
            }else{
                $hash->hash = md5(date('Y-m-d H:i:s'));
                $hash->update();
            }
            
            if(!empty($invalid_stores)){
                $writer = WriterFactory::create(Type::CSV); 
                $writer->openToBrowser('invalid stores.txt');
                $writer->addRows($invalid_stores); 
                $writer->close();
            }else{
                Session::flash('flash_message', 'Store Masterfile successfully uploaded.');
                Session::flash('flash_class', 'alert-success');
                return redirect()->route("audits.stores",$id); 
            }

            

		   	   
		}else{
			Session::flash('flash_message', 'Error uploading masterfile.');
			Session::flash('flash_class', 'alert-danger');
			return redirect()->route("audits.uploadstores",$id);	
		}
		
    }
}
