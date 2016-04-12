<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSosLookupIdOnAuditStoreSosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('audit_store_sos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Schema::table('audit_store_sos', function(Blueprint $table) {
            $table->integer('audit_sos_lookup_id')->unsigned();
            $table->foreign('audit_sos_lookup_id')->references('id')->on('audit_sos_lookups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_store_sos', function(Blueprint $table) {
            $table->dropForeign(['audit_sos_lookup_id']);
            $table->dropColumn(['audit_sos_lookup_id']);
        });
    }
}
