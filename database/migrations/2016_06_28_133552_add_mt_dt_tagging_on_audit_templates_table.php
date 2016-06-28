<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMtDtTaggingOnAuditTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_templates', function (Blueprint $table) {
            $table->integer('template_type')->default('1')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_templates', function (Blueprint $table) {
            $table->dropColumn(['template_type']);
        });
    }
}
