<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTalentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {    Schema::create('talent', function (Blueprint $table) {
            $table->increments('talentid')->unique();
            $table->string('lastname');
            $table->string('firstname');
            $table->string('age');
            $table->string('contactno');
            $table->string('address');
            $table->string('emailaddress');
            $table->string('groupname');
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
