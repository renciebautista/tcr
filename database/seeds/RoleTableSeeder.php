<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    	\DB::table('roles')->truncate();
    	\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $admin = new Role();
		$admin->name         = 'admin';
		$admin->display_name = 'User Administrator'; // optional
		$admin->description  = 'User is allowed to manage and edit other users'; // optional
		$admin->save();

		$field = new Role();
		$field->name         = 'field';
		$field->display_name = 'Field Personel'; // optional
		$field->description  = 'User is the auditor on site.'; // optional
		$field->save();

		$field = new Role();
		$field->name         = 'manager mt';
		$field->display_name = 'Manager Personel for MT'; // optional
		$field->description  = 'User is allowed to view reports only'; // optional
		$field->save();

		$field = new Role();
		$field->name         = 'manager mag';
		$field->display_name = 'Manager Personel for MAG'; // optional
		$field->description  = 'User is allowed to view reports only'; // optional
		$field->save();
    }
}
