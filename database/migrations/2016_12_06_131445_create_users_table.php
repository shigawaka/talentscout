<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('roleID');
            $table->string('firstname', 255);
            $table->string('lastname', 255);
            $table->integer('age', 2);
            $table->string('gender');
            $table->string('contactno');
            $table->string('address');
            $table->string('emailaddress');
            $table->string('group');
            $table->string('username', 255);
            $table->string('password', 255);
            $table->string('profile_image', 255);
            $table->string('profile_description', 255);
            $table->string('confirmation_code');
            $table->string('confirmed');
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
        Schema::drop('users');
    }
}
