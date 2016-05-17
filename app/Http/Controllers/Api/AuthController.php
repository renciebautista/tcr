<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\UpdatedHash;

class AuthController extends Controller
{
    public function auth(Request $request){
        $usernameinput = $request->email;
        $password = $request->pwd;

        if (\Auth::attempt(array('username' => $usernameinput, 'password' => $password), false))
        {
            $user = \Auth::user();

            $hash = UpdatedHash::find(1);
            $user->hash =  $hash->hash;
            
            return response()->json($user);
        }else{
        	return response()->json(array('msg' => 'user not found', 'status' => 0));
        }
    }
}
