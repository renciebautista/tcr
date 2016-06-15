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
    }
}
