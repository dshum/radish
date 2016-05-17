<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('login');
			$table->string('password');
			$table->string('email');
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->mediumText('parameters')->nullable();
			$table->boolean('superuser')->nullable();
			$table->boolean('banned')->nullable();
			$table->timestamp('last_login')->nullable();
			$table->timestamps();
			$table->engine = 'InnoDB';
			$table->unique('login');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('admin_users');
	}

}
