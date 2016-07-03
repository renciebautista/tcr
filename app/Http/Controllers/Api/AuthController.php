<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\UpdatedHash;
use App\Audit;

class AuthController extends Controller
{
    public function auth(Request $request){
        $usernameinput = $request->email;
        $password = $request->pwd;

        if (\Auth::attempt(array('username' => $usernameinput, 'password' => $password), false))
        {
            if(\Auth::attempt(array('active'=>0),false))
            {                
                return response()->json(array('msg' => 'account was suspended', 'status' => 0));    
            }
            else{            

                $user = \Auth::user();
                $hash = UpdatedHash::find(1);
                $audits = Audit::select('id', 'description')->get();
                $user->hash =  $hash->hash;
                $user->audits = $audits;
                $user->role_name = $user->roles[0]->name;
                
                return response()->json($user);
            }
        }else{
            return response()->json(array('msg' => 'user not found', 'status' => 0));
        }
    }
}
