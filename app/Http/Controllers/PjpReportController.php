<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\CheckIn;

class PjpReportController extends Controller
{
    public function frequency(){
    	$checkins = CheckIn::all();
    	return view('pjpreport.frequency', compact('checkins'));
    }
}
