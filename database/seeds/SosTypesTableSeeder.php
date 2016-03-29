<?php

use Illuminate\Database\Seeder;

class SosTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sos_types')->truncate();

		DB::statement("INSERT INTO sos_types (id, sos) VALUES
			(1, 'CAIP'),
			(2, 'NON-CAIP');");
    }
}
