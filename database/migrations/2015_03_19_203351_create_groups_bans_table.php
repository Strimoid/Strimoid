<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsBansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('group_bans', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('group_id')->unsigned();
			$table->foreign('group_id')
				->references('id')->on('groups')
				->onDelete('cascade');

			$table->integer('moderator_id')->unsigned();
			$table->foreign('moderator_id')
				->references('id')->on('users');

			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')
				->references('id')->on('users')
				->onDelete('cascade');

			$table->string('reason')->nullable();

			$table->timestamps();
			$table->timestamp('expires_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('group_bans');
	}

}
