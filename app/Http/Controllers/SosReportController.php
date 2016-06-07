<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;

class SosReportController extends Controller
{
    public function index(){
    	$soss = PostedAudit::getSos();
    	return view('sosreport.index', compact('soss'));
    }
}
