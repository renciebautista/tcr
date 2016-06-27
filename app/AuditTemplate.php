<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FormCategory;
use App\FormGroup;
use App\MyClasses\SurveyQuestion;

class AuditTemplate extends Model
{

	protected $fillable = ['audit_id', 'channel_code', 'description'];

    public static function import($id,$file_path){
        \DB::beginTransaction();
        try {
        	$sheetNames = \Excel::load($file_path)->getSheetNames();
        	if($sheetNames[1] == 'Sub Forms'){

                $store = AuditStore::where('template',$sheetNames[0])
                    ->where('audit_id',$id)
                    ->first();
                if(!empty($store)){
                    $template = AuditTemplate::firstOrCreate(['audit_id' => $id, 'channel_code' => $store->channel_code, 'description' => $sheetNames[0]]);

                    TemplateSubForm::where('audit_template_id', $template->id)->delete();

                    \Excel::selectSheets('Sub Forms')->load($file_path, function($reader) use ($template) {
                        $results = $reader->get();
                        $sub_forms = [];
                        foreach ($results as $key => $row) {
                            $rowtype = '';
                            if($row->type == 'Single Line Text'){
                                $rowtype = 'Single-Line Text';
                            }else{
                                $rowtype = $row->type;
                            }
                            $sub_forms[] = ['audit_template_id' => $template->id, 'code' => $row->survey_code, 'prompt' => addslashes($row->survey_question),
                                'required' => $row->required, 'type' => $rowtype, 'choices' => $row->choices, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
                        }
                        if(!empty($sub_forms)){
                            TemplateSubForm::insert($sub_forms);
                        }
                    });

                    $template->updated_at = date('Y-m-d H:i:s');
                    $template->update();

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

                    \Excel::selectSheets($sheetNames[0])->load($file_path, function($reader) use ($template) {
                        $results = $reader->get();
                        // dd($results);
                        foreach ($results as $key => $row) {
                            if(!empty($row->activity)){
                                if(!empty($row->activity)){
                                    $category = FormCategory::firstOrCreate(['audit_id' => $template->audit_id, 'category' => $row->activity]);
                                }

                                if(!empty($row->group)){
                                    $group = FormGroup::firstOrCreate(['audit_id' => $template->audit_id, 'group_desc' => $row->group]);
                                }

                                $type = $row->type;

                                if(strtoupper($type) == 'DOUBLE'){
                                    $form_type = FormType::where('form_type', "NUMERIC")->first();
                                }else{
                                    $form_type = FormType::where('form_type', strtoupper($type))->first();
                                } 

                                if($form_type->id == 11){ // Computational 
                                    $index1 = array();
                                    $index2 = array();
                                    preg_match_all('/{(.*?)}/', $row->choices, $matches);
                                    foreach ($matches[1] as $key => $a ){
                                        $data = \DB::table('template_sub_forms')
                                            ->where('audit_template_id', $template->id)
                                            ->where('code',$a)->first();
                                        if(!empty($data)){
                                            $surveyQuestion = new SurveyQuestion();
                                            $surveyQuestion->template = $template;
                                            $surveyQuestion->form_code = $a;
                                            $surveyQuestion->form_type = $data->type;
                                            $surveyQuestion->required = $data->required;
                                            $surveyQuestion->prompt = $data->prompt;
                                            $surveyQuestion->choices = $data->choices;
                                            $surveyQuestion->expected_answer = $data->expected_answer;

                                            $other_form = Form::createForm($surveyQuestion);
                                            $index1[$a] = $other_form->id;
                                            $index2[$a] = $other_form->prompt.'_'.$other_form->id;
                                        }else{
                                            $other_form = \DB::table('forms')
                                                ->where('audit_template_id',$template->id)
                                                ->where('code',$a)->first();

                                            $index1[$a] = '!'.$other_form->id;
                                            $index2[$a] = $other_form->prompt.'_'.$other_form->id;
                                        }
                                    }
                                    $formula1 = $row->choices;
                                    $formula2 = $row->choices;
                                    foreach ($matches[1] as $key => $a ){
                                        $formula1 = str_replace('{'.$a.'}',$index1[$a], $formula1);
                                        $formula2 = str_replace('{'.$a.'}', ' :'.$index2[$a].': ', $formula2);
                                    }

                                    $surveyQuestion = new SurveyQuestion();
                                    $surveyQuestion->template = $template;
                                    $surveyQuestion->form_code = $row->survey_code;
                                    $surveyQuestion->form_type = $row->type;
                                    $surveyQuestion->required = $row->required;
                                    $surveyQuestion->prompt = $row->survey_question;
                                    $surveyQuestion->choices = $formula1;
                                    $surveyQuestion->image = $row->image;
                                    $surveyQuestion->computational = $formula2;
                                    $surveyQuestion->default_answer = $row->default_answer;
                                    $surveyQuestion->sku_code = $row->sku_code;

                                    $survey = Form::createForm($surveyQuestion);
                                }elseif ($form_type->id == 12) { // Conditional
                                    preg_match_all('/(.*?){(.*?)}/', $row->choices, $matches);
                                    $data_con = array();
                                    $options = explode("~", $row->choices);

                                    foreach ($options as $option) {
                                        $with_value = preg_match('/{(.*?)}/', $option, $match);
                                        $x1 = array();
                                        $x2 = array();
                                        $_opt1 = "";
                                        $_opt2 = "";
                                        if($with_value){
                                            $codes = explode('^', $match[1]);
                                            if(count($codes)> 0){
                                                foreach ($codes as $code) {
                                                    if($code != ''){
                                                        $other_data = \DB::table('template_sub_forms')
                                                            ->where('audit_template_id', $template->id)
                                                            ->where('code',$code)->first();
                                                        if(empty($other_data)){
                                                            $other_data = \DB::table('forms')
                                                                ->where('audit_template_id',$template->id)
                                                                ->where('code',$code)->first();
                                                        }

                                                        $surveyQuestion = new SurveyQuestion();
                                                        $surveyQuestion->template = $template;
                                                        $surveyQuestion->form_code = $code;
                                                        $surveyQuestion->form_type = $other_data->type;
                                                        $surveyQuestion->required = $other_data->required;
                                                        $surveyQuestion->prompt = $other_data->prompt;
                                                        $surveyQuestion->choices = $other_data->choices;
                                                        $surveyQuestion->expected_answer = $other_data->expected_answer;
                   
                                                        $other_form = Form::createForm($surveyQuestion);
                                                                    
                                                        $x1[] = $other_form->id;
                                                        $x2[] = $other_form->prompt.'_'.$other_form->id;
                                                    }
                                                }
                                            }
                                            if(count($x1) > 0){
                                                $_opt1 = implode("^", $x1);
                                            }
                                            if(count($x2) > 0){
                                                $_opt2 = implode("^", $x2);
                                            }
                                        }
                                        $data_con[] = ['option' => strtoupper(strtok($option, '{')), 'condition' => $_opt1, 'condition_desc' => $_opt2];
                                    }

                                    $surveyQuestion = new SurveyQuestion();
                                    $surveyQuestion->template = $template;
                                    $surveyQuestion->form_code = $row->survey_code;
                                    $surveyQuestion->form_type = $row->type;
                                    $surveyQuestion->required = $row->required;
                                    $surveyQuestion->prompt = $row->survey_question;
                                    $surveyQuestion->choices = $row->choices;
                                    $surveyQuestion->expected_answer = $row->expected_answer;
                                    $surveyQuestion->image = $row->image;
                                    $surveyQuestion->conditional = $data_con;
                                    $surveyQuestion->default_answer = $row->default_answer;
                                    $surveyQuestion->sku_code = $row->sku_code;

                                    $survey = Form::createForm($surveyQuestion);
                                }else{ // Ordinary 
                                    $surveyQuestion = new SurveyQuestion();
                                    $surveyQuestion->template = $template;
                                    $surveyQuestion->form_code = $row->survey_code;
                                    $surveyQuestion->form_type = $row->type;
                                    $surveyQuestion->required = $row->required;
                                    $surveyQuestion->prompt = $row->survey_question;
                                    $surveyQuestion->choices = $row->choices;
                                    $surveyQuestion->expected_answer = $row->expected_answer;
                                    $surveyQuestion->image = $row->image;
                                    $surveyQuestion->default_answer = $row->default_answer;
                                    $surveyQuestion->sku_code = $row->sku_code;

                                    $survey = Form::createForm($surveyQuestion);
                                }

                                // order category
                                $cat_order = 1;
                                $a_cat_id = 0;
                                $clast_cnt = AuditTemplateCategory::getLastOrder($template->id);
                                if(empty($clast_cnt)){
                                    $a_cat = AuditTemplateCategory::create(['category_order' => $cat_order,
                                        'audit_template_id' => $template->id, 
                                        'form_category_id' => $category->id]);
                                    $a_cat_id = $a_cat->id;
                                }else{
                                    $cat = AuditTemplateCategory::categoryExist($template->id, $category->id);
                                    if(empty($cat)){
                                        $cat_order = $clast_cnt->category_order + 1;
                                        $a_cat = AuditTemplateCategory::create(['category_order' => $cat_order,
                                            'audit_template_id' => $template->id, 
                                            'form_category_id' => $category->id]);
                                        $a_cat_id = $a_cat->id;
                                    }else{
                                        $a_cat_id = $cat->id;
                                    }
                                }

                                // order group
                                $grp_order = 1;
                                $a_grp_id = 0;
                                $glast_cnt = AuditTemplateGroup::getLastOrder($a_cat_id);
                                if(empty($glast_cnt)){
                                    $a_grp = AuditTemplateGroup::create(['group_order' => $grp_order,
                                        'audit_template_id' => $template->id, 
                                        'audit_template_category_id' => $a_cat_id, 
                                        'form_group_id' => $group->id]);
                                    $a_grp_id = $a_grp->id;
                                }else{
                                    $grp = AuditTemplateGroup::categoryExist($a_cat_id, $group->id);
                                    if(empty($grp)){
                                        $grp_order = $glast_cnt->group_order + 1;
                                        $a_grp = AuditTemplateGroup::create(['group_order' => $grp_order,
                                            'audit_template_id' => $template->id, 
                                            'audit_template_category_id' => $a_cat_id, 
                                            'form_group_id' => $group->id]);
                                        $a_grp_id = $a_grp->id;
                                    }else{
                                        $a_grp_id = $grp->id;
                                    }
                                }

                                $order = 1;
                                $a_frm_id = 0;
                                $last_cnt = AuditTemplateForm::getLastOrder($a_grp_id);
                                if(!empty($last_cnt)){
                                    $order = $last_cnt->order + 1;
                                }
                                AuditTemplateForm::create(array(
                                    'audit_template_id' => $template->id,
                                    'audit_template_group_id' => $a_grp_id,
                                    'order' => $order,
                                    'form_id' => $survey->id
                                    ));
                            }
                        }
                    });

                    $data['status'] = 1;
                }else{
                    $data['status'] = 0;
                }
        		
        	}else{
        		$data['status'] = 0;
        	}
            \DB::commit();
        	return $data;

        } catch (\Exception $e) {
            \DB::rollback();
            dd($e);
        }
  
    }
}
