<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Role;
use DB;
use Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$users = User::get();
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$roles = Role::orderBy('name')->lists('name', 'id');
        return view('user.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'same:password',
            'role' => 'required|integer|min:1'
        ]);

        $role = Role::findOrFail($request->role);

        $user = new User();
        $user->name = strtoupper($request->name);
        $user->username = $request->username;
        if($request->has('email')){
        	$user->email = $request->email;
        }
        
        $user->password = \Hash::make($request->password);
        $user->save();


        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $user->roles()->attach($role);
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Session::flash('flash_message', 'User successfully added.');
        Session::flash('flash_class', 'alert-success');

        return redirect()->route("users.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
