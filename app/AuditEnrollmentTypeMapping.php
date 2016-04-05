<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditEnrollmentTypeMapping extends Model
{
    protected $fillable = ['audit_id','enrollment_type_id', 'value'];
    public $timestamps = false;

    public function enrollmentType(){
    	return $this->belongsTo('App\EnrollmentType', 'enrollment_type_id');
    }
}
