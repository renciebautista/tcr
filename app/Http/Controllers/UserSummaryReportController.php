<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class UserSummaryReportController extends Controller
{
    public function index(){
    	return view('usersummaryreport.index');
    }

    public function show($id){
    	return view('usersummaryreport.show');
    }
}
