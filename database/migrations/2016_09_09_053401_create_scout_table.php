<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('scout', function (Blueprint $table) {
            $table->increments('scoutid')->unique();
            $table->string('scoutname');
            $table->string('address');
            $table->string('emailaddress');
            $table->string('contactno');
            $table->string('username');
            $table->string('password');
             $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
