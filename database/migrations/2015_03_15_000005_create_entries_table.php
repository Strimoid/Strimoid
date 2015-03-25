<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('entries', function(Blueprint $table)
		{
			$table->increments('id');

			$table->text('text');
			$table->text('text_source');

			// Counters
			$table->integer('replies_count')->unsigned()->default(0);

			// Relations
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');

			$table->integer('group_id')->unsigned();
			$table->foreign('group_id')->references('id')->on('groups');

			// Vote counts
			$table->integer('uv')->unsigned()->default(0);
			$table->integer('dv')->unsigned()->default(0);
			$table->integer('score')->unsigned()->default(0);

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
		Schema::drop('entries');
	}

}
