<?php

use Illuminate\Database\Seeder;

class FormTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('form_types')->truncate();

		DB::statement("INSERT INTO form_types (id, form_type) VALUES
			(1, 'LABEL'),
			(2, 'IMAGE CAPTURE'),
			(3, 'NUMERIC'),
			(4, 'SINGLE-LINE TEXT'),
			(5, 'MULTI-LINE TEXT'),
			(6, 'SIGNATURE CAPTURE'),
			(7, 'DATE'),
			(8, 'TIME'),
			(9, 'MULTI ITEM SELECT'),
			(10, 'SINGLE ITEM SELECT'),
			(11, 'COMPUTATIONAL'),
			(12, 'CONDITIONAL'),
			(13, 'HEADER'),
			(14, 'TEXTFILE');");
    }
}
