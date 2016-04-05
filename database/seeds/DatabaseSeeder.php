<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call('FormTypesTableSeeder');
    	$this->command->info('Form type table seeded!');
        $this->call('SosTypesTableSeeder');
        $this->command->info('SOS type table seeded!');
        $this->call('EnrollmentTypeTableSeeder');
        $this->command->info('Enrollment type table seeded!');
    	DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
