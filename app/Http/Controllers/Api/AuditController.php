<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Audit;
use App\PostedAudit;

class AuditController extends Controller
{
    public function index(){
    	$audits = Audit::select('id', 'description')
    		->orderBy('description')
    		->get();
    	return $audits->toJson();
    }

    public function postedaudits(){
    	$audits = PostedAudit::getAudits();
    	return $audits->toJson();
    }
}
