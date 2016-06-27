<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\CheckIn;
use App\PostedAudit;

class PjpReportController extends Controller
{
    public function index(){
    	$users = PostedAudit::getUsers()->lists('name','user_id');
        $audits = PostedAudit::getAudits()->lists('description','audit_id');
    	$checkins = [];
    	return view('pjpreport.frequency', compact('checkins', 'users', 'audits'));
    }

    public function create(Request $request){
        $checkins = CheckIn::search($request);
        if($request->submit == 'process'){
            $request->flash();
            $users = PostedAudit::getUsers()->lists('name','user_id');
            $audits = PostedAudit::getAudits()->lists('description','audit_id');
            
            return view('pjpreport.frequency', compact('checkins', 'users', 'audits'));
        }else{
            \Excel::create('PJP Frequency Report', function($excel) use ($checkins) {
                $excel->sheet('Sheet1', function($sheet) use ($checkins) {
                    $sheet->fromModel($checkins,null, 'A1', true);
                })->download('xls');

            });
        }
    }
}
