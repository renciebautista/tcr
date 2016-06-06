<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class SosReportController extends Controller
{
    public function index(){
    	return view('sosreport.index');
    }
}
