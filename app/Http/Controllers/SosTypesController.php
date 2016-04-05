<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\SosType;

class SosTypesController extends Controller
{
    public function index(){
    	$sostypes = SosType::all();
    	return view('sostypes.index', compact('sostypes'));
    }
}
