<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFollowedUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_followed_users', function(Blueprint $table)
		{
			$table->integer('source_id')->unsigned();
			$table->foreign('source_id')
				->references('id')->on('users')
				->onDelete('cascade');

			$table->integer('target_id')->unsigned();
			$table->foreign('target_id')
				->references('id')->on('users')
				->onDelete('cascade');

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
		Schema::drop('user_followed_users');
	}

}
