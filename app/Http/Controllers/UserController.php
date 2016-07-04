<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Role;
use App\ManagerFields;
use DB;
use Session;
use Auth;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$users = User::orderBy('name')->get();
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
        $user = User::where('id',$id)->first();

        $myrole = Role::myroleid($id)->role_id;
        
        // $myrole = Role::myrole($myroleid->role_id);
        
        // $roles = Role::where('id','!=',$myroleid->role_id)->orderBy('name')->lists('name','id');
        $roles = Role::orderBy('name')->lists('name','id');                
        return view('user.edit',['user'=>$user,'myrole'=>$myrole,'roles'=>$roles]);
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
        $user = User::findOrFail($id);
        $role = Role::findOrFail($request->role);
        $user->email = $request->email;        

        $role_user = DB::table('role_user')->where('user_id',$id);
        $role_user->delete();
           
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $user->roles()->attach($role);
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $user->update();
        

        return redirect('users');
    }

    public function updatestatus($id){

        $user = User::findOrFail($id);
        
        if($user->active === 1){
                $user->active = 0;
                $user->update();
                Session::flash('flash_message', 'User was successfully Deactivated.');
                Session::flash('flash_class', 'alert-success');
                return redirect('users');
            }
        elseif($user->active === 0){
                $user->active = 1;     
                $user->update();
                Session::flash('flash_message', 'User was successfully Activated.');
                Session::flash('flash_class', 'alert-success');
                return redirect('users');
            }          
    }

    public function managefields($id){

        $user = User::where('id',$id)->first();
        $fields = ManagerFields::where('managers_id',$id)->get();                 
        return view('user.managefields',compact('user','fields'));
    }
    public function managefields_create($id){        
        $manager = User::where('id',$id)->first();
        $already_tagged = ManagerFields::where('managers_id',$id)->get();

        $data = [];
        foreach ($already_tagged as $value) {
            $data[] =  $value->fields_id;
        }
        
        $users = User::whereNotIn('id', $data)->orderBy('name')->get();

        return view('user.managefieldscreate',compact('users','manager'));
    }

    public function managefields_store(Request $request){

        $user = $request->get('tagfields');
        $manager_id = $request->get('manager_id');
        if(is_array($user)){

            foreach($user as $users)
            {                    
                if(!empty($users))
                {
                    DB::table('manager_fields')->insert(array([
                    'managers_id' => $manager_id,
                    'fields_id' => $users,                
                    ]));    
                }                
            }
        }        
        return redirect()->action('UserController@managefields', [$manager_id]);
    }
    public function managefieldsupdate(Request $request){
        
        $manager = $request->get('manager_id');        
        $fields = $request->get('fields_id');
        $tagged = DB::table('manager_fields')->where('fields_id',$fields)->where('managers_id',$manager)->delete();        
        Session::flash('flash_message', 'Field was successfully Untagged.');
        Session::flash('flash_class', 'alert-success');
        return redirect()->action('UserController@managefields', [$manager]);
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
