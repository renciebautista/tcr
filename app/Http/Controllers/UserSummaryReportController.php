<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PostedAudit;
use App\PostedAuditCategorySummary;
use App\Audit;
use App\User;
use App\Role;
use App\UserSummary;
use Auth;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class UserSummaryReportController extends Controller
{
    public function index(){
        
        set_time_limit(0); 
        
        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);  

        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

            $users = PostedAudit::getUsers($auth_user)->lists('name','user_id');
            $use = PostedAudit::getUsers($auth_user);            

        }

        if($role->role_id === 3){

            $temp = PostedAudit::getTemplatesMT($auth_user);
            $users = PostedAudit::getUsersMT($temp)->lists('name','user_id');            
            $use = PostedAudit::getUsersMT($temp); 

        }
        $user_summaries = PostedAudit::getUserSummaryDefault($use);

        $posted_audits = $user_summaries; 
        $audits = PostedAudit::getAudits()->lists('description','audit_id');

        $p_store_average =PostedAudit::getPerfectStoreAverageInUserReport($posted_audits);
        $osa_average = PostedAudit::getOsaAverage($posted_audits);
        $npi_average = PostedAudit::getNpiAverage($posted_audits);
        $planogram_average = PostedAudit::getPlanogramAverage($posted_audits);

    	return view('usersummaryreport.index', compact('user_summaries','users','audits','p_store_average','npi_average','osa_average','planogram_average'));
    }

    public function create(Request $request){
        
        $auth_user = Auth::id(); 
        $id = $auth_user;
        $role = Role::myroleid($id);

        if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

            $use = PostedAudit::getUsers($auth_user);
               
        }
        if($role->role_id === 3){

            $temp = PostedAudit::getTemplatesMT($auth_user);
            $users = PostedAudit::getUsersMT($temp)->lists('name','user_id');            
            $use = PostedAudit::getUsersMT($temp); 
        }

        $user_summaries = PostedAudit::getUserSummary($request,$use);    
        $posted_audits = $user_summaries;
        $p_store_average =PostedAudit::getPerfectStoreAverageInUserReport($posted_audits);
        $osa_average = PostedAudit::getOsaAverage($posted_audits);
        $npi_average = PostedAudit::getNpiAverage($posted_audits);
        $planogram_average = PostedAudit::getPlanogramAverage($posted_audits);


        if($request->submit == 'process'){
            
            $request->flash();

            if($role->role_id === 1 || $role->role_id === 2 || $role->role_id === 4){

                $users = PostedAudit::getUsers($auth_user)->lists('name','user_id');

            }
            if($role->role_id === 3){

                $temp = PostedAudit::getTemplatesMT($auth_user);
                $users = PostedAudit::getUsersMT($temp)->lists('name','user_id');     
            }
            
            $audits = PostedAudit::getAudits()->lists('description','audit_id');
            
            return view('usersummaryreport.index', compact('user_summaries','users','audits','p_store_average','npi_average','osa_average','planogram_average'));
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
