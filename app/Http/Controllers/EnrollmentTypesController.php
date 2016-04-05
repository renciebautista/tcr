<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\EnrollmentType;

class EnrollmentTypesController extends Controller
{
    public function index(){
    	$enrollmenttypes = EnrollmentType::all();
    	return view('enrollmenttypes.index', compact('enrollmenttypes'));
    }
}
