<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function auth(Request $request){
        $usernameinput = $request->email;
        $password = $request->pwd;

        $field = filter_var($usernameinput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (\Auth::attempt(array($field => $usernameinput, 'password' => $password), false))
        {
            $user = \Auth::user();
            return response()->json($user);
        }else{
        	return response()->json(array('msg' => 'user not found', 'status' => 0));
        }
    }
}
