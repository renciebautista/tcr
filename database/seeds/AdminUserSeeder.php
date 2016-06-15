<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    	$users = User::all();
    	foreach ($users as $user) {
    		$user->roles()->attach(2);
    	}
    	

		User::insert(array(
			'name'     => 'admin',
			'email'    => 'admin@tcr.com',
			'username' => 'admin',
			'password' => Hash::make('password'),
		));

		$admin = User::where('username', 'admin')->first();
		$admin->roles()->attach(1);
        
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
