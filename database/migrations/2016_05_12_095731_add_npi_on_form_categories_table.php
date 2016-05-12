<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNpiOnFormCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_categories', function (Blueprint $table) {
            $table->boolean('npi')->after('osa');
            $table->boolean('plano')->after('npi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_categories', function (Blueprint $table) {
            $table->dropColumn(['npi', 'plano']);
        });
    }
}
