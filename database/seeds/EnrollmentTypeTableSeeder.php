<?php

use Illuminate\Database\Seeder;

class EnrollmentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('enrollment_types')->truncate();

		DB::statement("INSERT INTO enrollment_types (id, enrollmenttype, value) VALUES
			(1, 'NON ENVISION', 10),
			(2, 'ENVISION', 10);");
    }
}
