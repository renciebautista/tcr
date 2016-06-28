<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;

use App\Audit;
use App\AuditTemplate;
use App\UpdatedHash;

class AuditDtTemplateController extends Controller
{
    public function index($id){
    	$audit = Audit::findOrFail($id);
    	$templates = AuditTemplate::where('audit_id',$audit->id)
    		->where('template_type', 2)
    		->orderBy('updated_at', 'desc')->get();
    	return view('auditdttemplate.index', compact('audit', 'templates'));
    }

    public function create($id){
    	$audit = Audit::findOrFail($id);
    	return view('auditdttemplate.create',compact('audit'));
    }

    public function store(Request $request, $id){
    	if ($request->hasFile('file')){
            $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());
            $type = 2;
            $reply = AuditTemplate::import($id,$file_path,$type);

            if (\File::exists($file_path))
            {
                \File::delete($file_path);
            }
            if($reply['status'] == 1){
                $hash = UpdatedHash::find(1);
                if(empty($hash)){
                    UpdatedHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);
                }else{
                    $hash->hash = md5(date('Y-m-d H:i:s'));
                    $hash->update();
                }

                Session::flash('flash_message', 'DT Audit Template successfully uploaded.');
                Session::flash('flash_class', 'alert-success');
                return redirect()->route("audits.dttemplates",$id);    
            }else{
                Session::flash('flash_message', 'Invalid file format');
                Session::flash('flash_class', 'alert-danger');
                return redirect()->route("audits.uploaddttemplates",$id);
            }

		   	
		}else{
            Session::flash('flash_message', 'Error uploading masterfile.');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->route("audits.uploaddttemplates",$id);
        }
    }
}
