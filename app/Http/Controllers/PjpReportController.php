<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\CheckIn;
use App\PostedAudit;
use Auth;
use Input;
use Response;
use App\Role;
class PjpReportController extends Controller
{
    public function index(){

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

        $audits = PostedAudit::getAudits()->lists('description','audit_id');

    	$checkins =CheckIn::searchdef($use);

    	return view('pjpreport.frequency', compact('checkins', 'users', 'audits'));
    }

    public function create(Request $request){
        
        $auth_user = Auth::id();
        $id = $auth_user;
        $role = Role::myroleid($id);

        $use = PostedAudit::getUsers($auth_user); 

        $checkins = CheckIn::search($request,$use);

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
            return view('pjpreport.frequency', compact('checkins', 'users', 'audits'));
            
        }else{
            \Excel::create('PJP Frequency Report', function($excel) use ($checkins) {
                $excel->sheet('Sheet1', function($sheet) use ($checkins) {
                    $sheet->fromModel($checkins,null, 'A1', true);
                })->download('xls');

            });
        }
    }
    public function monthfilter(){

        $auth_user = Auth::id();
        $user = Input::get('users');        

        $id = $auth_user;
        $role = Role::myroleid($id);

        $audits = PostedAudit::getauditfiltersAFPjp($user)->lists('description','audit_id');

        return Response::json($audits);

    }   
}
