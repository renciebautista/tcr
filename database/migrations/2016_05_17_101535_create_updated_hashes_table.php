<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\UpdatedHash;

class CreateUpdatedHashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('updated_hashes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash');
            $table->timestamps();
        });

        UpdatedHash::create(['hash' => \Hash::make(date('Y-m-d H:i:s'))]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('updated_hashes');
    }
}
