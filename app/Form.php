<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MyClasses\SurveyQuestion;
 
use App\AuditMultiSelect;
use App\AuditSingleSelect;
use App\FormSingleSelect;
class Form extends Model
{
	public $timestamps = false;

    protected $fillable = ['audit_template_id', 'form_type_id', 'code', 'sku_code', 'prompt', 'required', 'expected_answer', 'default_answer', 'image', 'exempt'];

    public static function createForm(SurveyQuestion $survey){
    	$form_type = self::formType($survey->form_type);
    	switch ($survey->required) {
			case 't':
				$required = 1;
				break;
			case 'yes':
				$required = 1;
				break;
			case 'f':
				$required = 0;
				break;
			case 'no':
				$required = 0;
				break;
			default:
				$required = $survey->required;
				break;
		}
		

    	$form = Form::create(array(
			'audit_template_id' => $survey->template->id,
			'form_type_id' => $form_type->id,
			'code' => $survey->form_code,
			'sku_code' => $survey->sku_code,
			'prompt' => strtoupper($survey->prompt),
			'required' => $required,
			'image' => $survey->image,
			'exempt' => 0,		
		));

		if($form_type->id == 9){ // MULTI ITEM SELECT
			$choices = explode("~", $survey->choices);
			foreach ($choices as $choice) {
				$sel = AuditMultiSelect::firstOrCreate(array('audit_template_id' => $survey->template->id, 'option' => strtoupper($choice)));
				FormMultiSelect::create(array('audit_template_id' =>$survey->template->id, 'form_id' => $form->id, 'multi_select_id' => $sel->id));
			}

			if(!empty($survey->expected_answer)){
				$_form = Form::find($form->id);
				$ans = explode("^", $survey->expected_answer);
				$pos_ans = [];
				foreach ($ans as $value) {
					$_ans = AuditMultiSelect::where('audit_template_id',$survey->template->id)
						->where('option',strtoupper($value))->first();
					$pos_ans[] = $_ans->id;
				}
				if(!empty($pos_ans)){
					$_form->expected_answer = implode("^", $pos_ans);
					$_form->update();
				}
			}

			if(!empty($surveydefault_answer)){
				$_form = Form::find($form->id);
				$ans = explode("^", $default_answer);
				$pos_ans = [];
				foreach ($ans as $value) {
					$_ans = AuditMultiSelect::where('audit_template_id',$survey->template->id)
						->where('option',strtoupper($value))->first();
					$pos_ans[] = $_ans->id;
				}
				if(!empty($pos_ans)){
					$_form->default_answer = implode("^", $pos_ans);
					$_form->update();
				}
			}
		}

		if($form_type->id == 10){ // SINLE ITEM SELECT
			$choices = explode("~", $survey->choices);
			foreach ($choices as $choice) {
				if($choice == "1"){
					$opt = "YES";
				}elseif($choice == "0"){
					$opt = "NO";
				}else{
					$opt = $choice;
				}

				$sel = AuditSingleSelect::firstOrCreate(array('audit_template_id' => $survey->template->id, 'option' => strtoupper($opt)));
				FormSingleSelect::create(array('audit_template_id' =>$survey->template->id, 'form_id' => $form->id, 'audit_single_select_id' => $sel->id));
			}

			if(!empty($survey->expected_answer)){
				$_form = Form::find($form->id);
				$ans = explode("^", $survey->expected_answer);
				$pos_ans = [];
				foreach ($ans as $value) {
					$_ans = AuditSingleSelect::where('audit_template_id',$survey->template->id)
						->where('option',strtoupper($value))->first();
					$pos_ans[] = $_ans->id;
				}
				if(!empty($pos_ans)){
					$_form->expected_answer = implode("^", $pos_ans);
					$_form->update();
				}
			}

			if(!empty($surveydefault_answer)){
				$_form = Form::find($form->id);
				$ans = explode("^", $default_answer);
				$pos_ans = [];
				foreach ($ans as $value) {
					$_ans = AuditSingleSelect::where('audit_template_id',$survey->template->id)
						->where('option',strtoupper($value))->first();
					$pos_ans[] = $_ans->id;
				}
				if(!empty($pos_ans)){
					$_form->default_answer = implode("^", $pos_ans);
					$_form->update();
				}
			}
		}

		if($form_type->id == 11){ // Computational	
			FormFormula::create(['audit_template_id' =>$survey->template->id, 'form_id' => $form->id, 'formula' => $survey->choices, 'formula_desc' => $survey->computational]);
		}

		if($form_type->id == 12){ // Conditional
			foreach ($survey->conditional as $con_data) {
				$con = FormCondition::create(['audit_template_id' =>$survey->template->id,
					'form_id' => $form->id,
					'option' => $con_data['option'], 
					'condition' => $con_data['condition'], 
					'condition_desc' => $con_data['condition_desc']]);	
			}

			if(!empty($survey->expected_answer)){
				$_form = Form::find($form->id);

				$ans = explode("^", $survey->expected_answer);
				$pos_ans = [];
				foreach ($ans as $value) {
					$_ans = FormCondition::where('option',strtoupper($value))
						->where('form_id',$form->id)
						->first();
					$pos_ans[] = $_ans->id;
				}
				if(!empty($pos_ans)){
					$_form->expected_answer = implode("^", $pos_ans);
					$_form->update();
				}
			}

			if(!empty($survey->default_answer)){
				$_form = Form::find($form->id);
				$ans = FormCondition::where('option',strtoupper($survey->default_answer))
					->where('form_id',$form->id)
					->first();
				if(!empty($ans)){
					$_form->default_answer = $ans->id;
					$_form->update();
				}
			}
		}

		return $form;

    }

    private static function formType($type){
    	if(strtoupper($type) == 'DOUBLE'){
            return $form_type = FormType::where('form_type', "NUMERIC")->first();
        }else{
            return $form_type = FormType::where('form_type', strtoupper($type))->first();
        } 


    }
}
