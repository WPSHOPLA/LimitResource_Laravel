<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table) {
            $table->increments('id');
            $table->string('email', 64);
            $table->string('password', 64);
            $table->enum('role', [1, 2, 3]); //1 - Admin, 2 - Manager, 3 - User
            $table->string('remember_token', 128);
            $table->boolean('confirmed')->default(false);
            $table->dateTime('last_login');
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
        Schema::dropIfExists('users');
	}

}
