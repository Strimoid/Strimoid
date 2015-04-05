<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsModeratorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('group_moderators', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('group_id')->unsigned();
			$table->foreign('group_id')
				->references('id')->on('groups')
				->onDelete('cascade');

			$table->integer('moderator_id')->unsigned()->nullable();
			$table->foreign('moderator_id')
				->references('id')->on('users');

			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')
				->references('id')->on('users')
				->onDelete('cascade');

			$table->boolean('accepted');
			$table->enum('type', ['moderator', 'admin']);

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
		Schema::drop('group_moderators');
	}

}
