<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Http\Requests;
use App\Audit;
use App\AuditTemplate;
use App\UpdatedHash;
use App\TemplateSubForm;
use App\FormFormula;
use App\FormCondition;
use App\AuditTemplateForm;
use App\AuditTemplateGroup;
use App\AuditTemplateCategory;
use App\FormMultiSelect;
use App\FormSingleSelect;
use App\AuditMultiSelect;
use App\AuditSingleSelect;
use App\Form;

class AuditTemplateController extends Controller
{
    public function index($id){
    	$audit = Audit::findOrFail($id);
    	$templates = AuditTemplate::where('audit_id',$audit->id)
            ->where('template_type', 1)
            ->orderBy('updated_at', 'desc')->get();
    	return view('audittemplate.index', compact('audit', 'templates'));
    }

    public function create($id){
    	$audit = Audit::findOrFail($id);
    	return view('audittemplate.create',compact('audit'));
    }

    public function store(Request $request, $id){
    	if ($request->hasFile('file')){
            $file_path = $request->file('file')->move(storage_path().'/uploads/temp/',$request->file('file')->getClientOriginalName());
            $type = 1;
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

                Session::flash('flash_message', 'MT Audit Template successfully uploaded.');
                Session::flash('flash_class', 'alert-success');
                return redirect()->route("audits.templates",$id);    
            }else{
                Session::flash('flash_message', 'Invalid file format');
                Session::flash('flash_class', 'alert-danger');
                return redirect()->route("audits.uploadtemplates",$id);
            }

		   	
		}else{
            Session::flash('flash_message', 'Error uploading masterfile.');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->route("audits.uploadtemplates",$id);
        }
    }

    public function categories($id){
        $categories = [];
        return view('audittemplate.categories',compact('categories'));
    }

    public function destroy(Request $request, $id){
        $template = AuditTemplate::findOrFail($id);

        // dd($template);
        \DB::beginTransaction();

        try {
            TemplateSubForm::where('audit_template_id', $template->id)->delete();

            FormFormula::where('audit_template_id', $template->id)->delete();
            FormCondition::where('audit_template_id', $template->id)->delete();
            AuditTemplateForm::where('audit_template_id', $template->id)->delete();
            AuditTemplateGroup::where('audit_template_id', $template->id)->delete();
            AuditTemplateCategory::where('audit_template_id', $template->id)->delete();

            FormMultiSelect::where('audit_template_id', $template->id)->delete();
            FormSingleSelect::where('audit_template_id', $template->id)->delete();

            AuditMultiSelect::where('audit_template_id', $template->id)->delete();
            AuditSingleSelect::where('audit_template_id', $template->id)->delete();
            
            Form::where('audit_template_id', $template->id)->delete();

            $template->delete();

            \DB::commit();
            Session::flash('flash_message', 'Audit template successfully deleted!');
            Session::flash('flash_class', 'alert-success');
           return redirect()->back();

        } catch (Exception $e) {
            \DB::rollBack();
            Session::flash('flash_message', 'Error occured while deleting Audit template');
            Session::flash('flash_class', 'alert-danger');
            return redirect()->back();
        }
    }
}
