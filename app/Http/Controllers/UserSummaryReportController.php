<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;
use App\PostedAuditCategorySummary;
use App\Audit;
use App\User;
use App\UserSummary;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class UserSummaryReportController extends Controller
{
    public function index(){
    	$users = PostedAudit::getUsers()->lists('name','user_id');
    	$audits = PostedAudit::getAudits()->lists('description','audit_id');
    	$user_summaries = PostedAudit::getUserSummary();
    	return view('usersummaryreport.index', compact('user_summaries','users','audits'));
    }

    public function create(Request $request){
        $user_summaries = PostedAudit::getUserSummary($request);
        if($request->submit == 'process'){
            $request->flash();
            $users = PostedAudit::getUsers()->lists('name','user_id');
            $audits = PostedAudit::getAudits()->lists('description','audit_id');
            
            return view('usersummaryreport.index', compact('user_summaries','users','audits'));
        }
        else{
            set_time_limit(0);
            $writer = WriterFactory::create(Type::CSV); 
            $writer->openToBrowser('User Summary Report.csv');
            $writer->addRow(['user', 'audit_month', 'stores_mapped', 'pjp_target', 'stores_visited', 'to_be_visited', 'perfect_store']); 

            foreach($user_summaries as $row)
            {
                $row_data[0] = $row->name;
                $row_data[1] = $row->description;
                $row_data[2] = $row->mapped_stores;
                $row_data[3] = $row->target;
                $row_data[4] = $row->store_visited;
                $row_data[5] = $row->mapped_stores - $row->store_visited;
                $row_data[6] = $row->perfect_store;
                $writer->addRow($row_data); // add multiple rows at a time
            }
            
            
            $writer->close();
        }
    }

    public function show($audit_id,$user_id){
        $audit = Audit::findOrFail($audit_id);
        $user = User::findOrFail($user_id);
        $usersummary = UserSummary::getSummary($audit,$user);

    	$detail = $usersummary->detail;
    	$stores = $usersummary->stores;

    	return view('usersummaryreport.show', compact('detail', 'stores'));
    }
}
