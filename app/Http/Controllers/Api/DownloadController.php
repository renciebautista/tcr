<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\AuditStore;
use App\AuditTemplate;  
use App\AuditTemplateCategory;
use App\AuditTemplateGroup;
use App\AuditTemplateForm;
use App\Form;
use App\FormSingleSelect;
use App\FormMultiSelect;
use App\FormFormula;
use App\FormCondition;
use App\AuditSecondaryDisplayLookup;
use App\FormGroup;
use App\FormType;
use App\AuditOsaLookup;
use App\AuditSosLookup;
use App\FormCategory;
use DB;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class DownloadController extends Controller
{
  	public function index(Request $request){
        $user = $request->id;
        $type = $request->type;

        $user = User::find($user);
        
        $storelist = AuditStore::getUserStores($user);

        $result = array();
        $r_audit_list = array();
        foreach ($storelist as $store) {
            $r_audit_list[] = $store->audit_id;
            $audit_template = AuditTemplate::where('audit_id', $store->audit_id)->where('channel_code', $store->channel_code)->first();
            if(!empty($audit_template)){
                $result[] = $audit_template->id;
            }
            
        }

        $list = array_unique($result);
        $audit_list = array_unique($r_audit_list);

        // get store list
        if($type == "stores"){
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('stores.txt');
            $writer->addRow(['id', 'store', 'grade_matrix_id', 'audit_template_id', 'template', 'start_date', 'end_date','store_code']); 

            foreach ($storelist as $store) {
                $audit_template = AuditTemplate::where('audit_id', $store->audit_id)->where('channel_code', $store->channel_code)->first();
                if(!empty($audit_template)){
                    $store_ids[] = $store->id;
                    $data[0] = $store->id;
                    $data[1] = $store->store_name;
                    $data[2] = $store->auditEnrollment->value;
                    $data[3] = $audit_template->id;
                    $data[4] = $store->template;
                    $data[5] = 0;
                    $data[6] = 0;
                    $data[7] = 0;
                    $data[8] = 0;
                    $data[9] = $store->audit->start_date;
                    $data[10] = $store->audit->end_date;  
                    $data[11] = $store->store_code;      

                    $data[12] = $store->account;
                    $data[13] = $store->customer_code;
                    $data[14] = $store->customer;
                    $data[15] = $store->region_code;
                    $data[16] = $store->region;
                    $data[17] = $store->distributor_code;
                    $data[18] = $store->distributor;
                    $data[19] = $store->channel_code;
                    $data[20] = $store->audit_id;
                    $writer->addRow($data); 
                }
                
            }

            $writer->close();
        }

        // get template categories
        if($type == "temp_categories"){
            $categories = AuditTemplateCategory::select('audit_template_categories.id',
                    'audit_template_categories.audit_template_id', 
                    'audit_template_categories.form_category_id', 'form_categories.category', 'audit_template_categories.category_order',
                    'form_categories.perfect_store')
                    ->join('form_categories', 'form_categories.id' , '=', 'audit_template_categories.form_category_id')
                    ->whereIn('audit_template_id',$list)
                    ->orderBy('audit_template_id')
                    ->orderBy('category_order')
                    ->get();

            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('temp_category.txt');
            $writer->addRow(['id', 'audit_template_id','category_order', 'form_category_id','category',  'perfect_store']); 
            foreach ($categories as $category) {
                $data[0] = $category->id;
                $data[1] = $category->audit_template_id;
                $data[2] = $category->category_order;
                $data[3] = $category->form_category_id;
                $data[4] = $category->category;
                $data[5] = $category->perfect_store;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        // get template groups
        if($type == "temp_groups"){
            $c_list = array();
            $categories = AuditTemplateCategory::select('audit_template_categories.id',
                    'audit_template_categories.audit_template_id', 
                    'audit_template_categories.form_category_id', 'form_categories.category', 'audit_template_categories.category_order',
                    'form_categories.perfect_store')
                    ->join('form_categories', 'form_categories.id' , '=', 'audit_template_categories.form_category_id')
                    ->whereIn('audit_template_id',$list)
                    ->orderBy('audit_template_id')
                    ->orderBy('category_order')
                    ->get();
            foreach ($categories as $category) {
                $c_list[] = $category->id;
            }

            $groups = AuditTemplateGroup::select('audit_template_groups.id', 'audit_template_groups.form_group_id', 'form_groups.group_desc',
                'audit_template_categories.audit_template_id', 'audit_template_groups.group_order', 'audit_template_groups.audit_template_category_id', 'form_groups.perfect_store')
                ->join('audit_template_categories', 'audit_template_categories.id' , '=', 'audit_template_groups.audit_template_category_id')
                ->join('form_groups', 'form_groups.id' , '=', 'audit_template_groups.form_group_id')
                ->whereIn('audit_template_category_id',$c_list)
                ->orderBy('audit_template_id')
                ->orderBy('audit_template_category_id')
                ->orderBy('group_order')
                ->get();

            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('temp_group.txt');
            $writer->addRow(['id', 'audit_template_id', 'audit_template_category_id', 'group_order', 'form_group_id','group_desc','perfect_store']); 
            foreach ($groups as $group) {
                $data[0] = $group->id;
                $data[1] = $group->audit_template_id;
                $data[2] = $group->audit_template_category_id;
                $data[3] = $group->group_order;
                $data[4] = $group->form_group_id;
                $data[5] = $group->group_desc;
                $data[6] = $group->perfect_store;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        // get template questions
        if($type == "temp_questions"){
            // dd($list);
            $forms = AuditTemplateForm::select('audit_template_forms.id', 
                'audit_template_forms.order',
                'audit_template_forms.audit_template_group_id', 'audit_template_forms.audit_template_id',
                'audit_template_forms.form_id', 'forms.form_type_id', 'forms.prompt', 'forms.required', 'forms.expected_answer', 'forms.exempt', 'forms.image',
                'forms.default_answer')
                ->join('audit_template_groups', 'audit_template_groups.id', '=', 'audit_template_forms.audit_template_group_id')
                ->join('audit_template_categories', 'audit_template_categories.id', '=', 'audit_template_groups.audit_template_category_id')
                ->join('forms', 'forms.id', '=', 'audit_template_forms.form_id')
                ->join('form_categories', 'form_categories.id', '=', 'audit_template_categories.form_category_id')
                ->join('form_groups', 'form_groups.id', '=', 'audit_template_groups.form_group_id')
                ->whereIn('audit_template_forms.audit_template_id',$list)
                ->get();
            // dd($forms);
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('questions.txt');
            $writer->addRow(['id', 'order', 'audit_template_group_id', 'audit_template_id', 'form_id', 'form_type_id', 'prompt', 'required', 'expected_answer', 'exempt', 'image', 'default_answer']); 
            foreach ($forms as $form) {
                $data[0] = $form->id;
                $data[1] = $form->order;
                $data[2] = $form->audit_template_group_id;
                $data[3] = $form->audit_template_id;
                $data[4] = $form->form_id;
                $data[5] = $form->form_type_id;
                $prompt = "null";
                if(!empty($form->prompt)){
                    $prompt = preg_replace("/[\\n\\r]+/", " ", $form->prompt);
                }
                $data[6] = $prompt;
                $data[7] = $form->required;
                $data[8] = $form->expected_answer;
                $data[9] = $form->exempt;
                $data[10] = $form->form->image;
                $data[11] = $form->default_answer;
                // var_dump($data);
                $writer->addRow($data); 
            }

            $writer->close();
        }

        // get template forms
        if($type == "temp_forms"){
            $forms = Form::whereIn('forms.audit_template_id',$list)
                ->get();
            
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('forms.txt');
            $writer->addRow(['id', 'audit_template_id', 'form_type_id', 'prompt', 'required', 'expected_answer', 'exempt', 'image','default_answer']); 
            foreach ($forms as $form) {
                $data[0] = $form->id;
                $data[1] = $form->audit_template_id;
                $data[2] = $form->form_type_id;
                $data[3] = preg_replace("/[\\n\\r]+/", " ", $form->prompt);
                $data[4] = $form->required;
                $data[5] = $form->expected_answer;
                $data[6] = $form->exempt;
                $data[7] = $form->image;
                $data[8] = $form->default_answer;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        // get single selects
        if($type == "single_selects"){
            $forms = Form::whereIn('forms.audit_template_id',$list)
                ->where('form_type_id',10)
                ->get();
            $form_ids = array();
            foreach ($forms as $form) {
                $form_ids[] = $form->id;
            }

            $selections = FormSingleSelect::select('form_single_selects.form_id','audit_single_selects.id', 'audit_single_selects.option')
                ->join('audit_single_selects', 'audit_single_selects.id', '=', 'form_single_selects.audit_single_select_id')
                ->whereIn('form_single_selects.form_id',$form_ids)
                ->orderBy('form_single_selects.form_id')
                ->get();

            // dd($selections);

            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('single_selects.txt');
            $writer->addRow(['form_id', 'id', 'option']); 
            foreach ($selections as $selection) {
                $data[0] = $selection->form_id;
                $data[1] = $selection->id;
                $data[2] = $selection->option;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        // get multiple selects
        if($type == "multi_selects"){
            $forms = Form::whereIn('forms.audit_template_id',$list)
                ->where('form_type_id',9)
                ->get();
            $form_ids = array();
            foreach ($forms as $form) {
                $form_ids[] = $form->id;
            }
            // dd($form_ids);

            $selections = FormMultiSelect::select('form_multi_selects.form_id', 'audit_multi_selects.id', 'audit_multi_selects.option')
                ->join('audit_multi_selects', 'audit_multi_selects.id', '=', 'form_multi_selects.audit_multi_select_id')
                ->whereIn('form_multi_selects.form_id',$form_ids)
                ->orderBy('form_multi_selects.form_id')
                ->orderBy('form_multi_selects.audit_multi_select_id')
                ->get();

            // dd($selections);

            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('multi_selects.txt');
            $writer->addRow(['form_id', 'id', 'option']); 
            foreach ($selections as $selection) {
                $data[0] = $selection->form_id;
                $data[1] = $selection->id;
                $data[2] = $selection->option;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        // get formulas
        if($type == "formulas"){
            $forms = Form::whereIn('forms.audit_template_id',$list)
                ->where('form_type_id',11)
                ->get();
            $form_ids = array();
            foreach ($forms as $form) {
                $form_ids[] = $form->id;
            }

            $formulas = FormFormula::select('form_formulas.form_id', 'formula')
                ->get();
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('formula.txt');
            $writer->addRow(['form_id', 'formula']); 
            foreach ($formulas as $formula) {
                $data[0] = $formula->form_id;
                $data[1] = $formula->formula;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        // get conditions
        if($type == "conditions"){
            $forms = Form::whereIn('forms.audit_template_id',$list)
                ->where('form_type_id',12)
                ->get();
            $form_ids = array();
            foreach ($forms as $form) {
                $form_ids[] = $form->id;
            }

            $conditions = FormCondition::select('form_conditions.form_id', 'option', 'condition', 'id')
                ->orderBy('id')
                ->get();
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('conditions.txt');
            $writer->addRow(['form_id', 'option', 'condition']); 
            foreach ($conditions as $condition) {
                $data[0] = $condition->form_id;
                $data[1] = $condition->option;
                $data[2] = $condition->condition;
                $data[3] = $condition->id;
                $writer->addRow($data); 
            }

            $writer->close();

        }

        // get secondary display lookup
        if($type == "secondary_lookups"){
            $store_ids = array();
            foreach ($storelist as $store) {
                $store_ids[] = $store->id;
            }
            $secondarydisplay = AuditSecondaryDisplayLookup::select('audit_store_id', 'form_category_id', 'brand')
                ->whereIn('audit_store_id',$store_ids)
                ->join('audit_secondary_displays', 'audit_secondary_displays.id', '=', 'audit_secondary_display_lookups.secondary_display_id')
                ->orderBy('audit_store_id')
                ->orderBy('form_category_id')
                ->get();

            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('secondarydisplay.txt');
            $writer->addRow(['audit_store_id', 'form_category_id', 'brand']); 
            foreach ($secondarydisplay as $category) {
                $data[0] = $category->audit_store_id;
                $data[1] = $category->form_category_id;
                $data[2] = strtoupper($category->brand);
                $writer->addRow($data); 
            }

            $writer->close();
        }

        // get secondary key list
        if($type == "secondary_lists"){
            $keylist = FormGroup::where('second_display', 1)
                ->whereIn('audit_id',$audit_list)
                ->get();

            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('secondary_keylist.txt');
            $writer->addRow(['id']); 
            foreach ($keylist as $list) {
                $data[0] = $list->id;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        // get osa key list
        if($type == "osa_lists"){
            $keylist = FormGroup::where('osa', 1)
                ->whereIn('audit_id',$audit_list)
                ->get();
            // dd($keylist);

            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('osa_keylist.txt');
            $writer->addRow(['id']); 
            foreach ($keylist as $list) {
                $data[0] = $list->id;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        if($type == "osa_lookups"){
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('osa_lookups.txt');
            $writer->addRow(['id', 'form_category_id', 'target', 'total', 'lookup_id']); 

            // dd($storelist);
            foreach ($storelist as $store) {
                // dd($store->id);
                $lookup = AuditOsaLookup::getOsaCategory($store->id);
                // dd($lookup);

                if(!empty($lookup)){

                    foreach ($lookup->categories as $category) {
                        $data[0] = $store->id;
                        $data[1] = $category->form_category_id;
                        $data[2] = $category->target;
                        $data[3] = $category->total;
                        $data[4] = $lookup->id;
                        $writer->addRow($data); 
                    }
                }
            }
            $writer->close();
        }

        // sos lookup
        if($type == "sos_lists"){
            $keylist = FormGroup::where('sos', 1)
                ->whereIn('audit_id',$audit_list)
                ->get();

            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('sos_keylist.txt');
            $writer->addRow(['id']); 
            foreach ($keylist as $list) {
                $data[0] = $list->id;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        
        if($type == "sos_lookups"){
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('sos_lookups.txt');
            $writer->addRow(['store_id', 'category_id', 'sos_id', 'less', 'value', 'sos_lookup_id']); 
            foreach ($storelist as $store) {
                $results = DB::select(DB::raw("select audit_store_sos.audit_store_id, 
                    audit_store_sos.form_category_id,audit_sos_lookup_details.sos_type_id,
                    audit_sos_lookup_details.less,audit_sos_lookup_details.value,audit_sos_lookup_details.audit_sos_lookup_id
                    from audit_store_sos
                    join audit_sos_lookup_details using(audit_sos_lookup_id, form_category_id, sos_type_id) 
                    where audit_store_sos.audit_store_id = :store_id"),array(
                   'store_id' => $store->id));
                foreach ($results as $result) {
                    $data[0] = $result->audit_store_id;
                    $data[1] = $result->form_category_id;
                    $data[2] = $result->sos_type_id;
                    $data[3] = $result->less;
                    $data[4] = $result->value;
                    $data[5] = $result->audit_sos_lookup_id;
                    $writer->addRow($data); 
                }
            }
            $writer->close();
        }

        // npi list
        if($type == "npi_lists"){
            $keylist = FormGroup::where('npi', 1)
                ->whereIn('audit_id',$audit_list)
                ->get();

            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('npi_keylist.txt');
            $writer->addRow(['id']); 
            foreach ($keylist as $list) {
                $data[0] = $list->id;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        if($type == "plano_lists"){
            $keylist = FormGroup::where('plano', 1)
                ->whereIn('audit_id',$audit_list)
                ->get();

            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('plano_keylist.txt');
            $writer->addRow(['id']); 
            foreach ($keylist as $list) {
                $data[0] = $list->id;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        if($type == "perfect_category_lists"){
            $keylist = FormCategory::where('perfect_store', 1)
                ->whereIn('audit_id',$audit_list)
                ->get();

            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('perfect_category_lists.txt');
            $writer->addRow(['id']); 
            foreach ($keylist as $list) {
                $data[0] = $list->id;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        if($type == "perfect_group_lists"){
            $keylist = FormGroup::where('perfect_store', 1)
                ->whereIn('audit_id',$audit_list)
                ->get();

            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('perfect_group_lists.txt');
            $writer->addRow(['id']); 
            foreach ($keylist as $list) {
                $data[0] = $list->id;
                $writer->addRow($data); 
            }

            $writer->close();
        }


        if($type == "form_types"){
            $form_types = FormType::all();
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('form_types.txt');
            $writer->addRow(['id', 'form_type']); 
            foreach ($form_types as $type) {
                $data[0] = $type->id;
                $data[1] = $type->form_type;
                $writer->addRow($data); 
            }

            $writer->close();
        }

        if($type == "image_lists"){
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('image_lists.txt');
            $writer->addRow(['image_name']); 
            $results = SurveyImage::all();
            // dd($results);
            foreach ($results as $result) {
                $data[0] = $result->images;
                $writer->addRow($data); 
            }
            $writer->close();
        }
   
    }

    public function image(Request $request){
        $filename = $request->name;
        $myfile = storage_path().'/surveyimages/'.$filename;

        if (!\File::exists($myfile))
        {
            echo "File not exists.";
        }else{
            return \Response::download($myfile, $filename);
        }
    }

    public function auditimage($folder,$filename){
        
        $myfile = storage_path().'/uploads/image/'.$folder."/".$filename;

        if (!\File::exists($myfile))
        {
            echo "File not exists.";
        }else{
            return \Response::download($myfile, $filename);
        }
        
    }
}
