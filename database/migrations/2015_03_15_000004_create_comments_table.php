<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *`
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function(Blueprint $table)
		{
			$table->increments('id');

			$table->text('text');
			$table->text('text_source');

			// Relations
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');

			$table->integer('group_id')->unsigned();
			$table->foreign('group_id')->references('id')->on('groups');

			$table->integer('content_id')->unsigned();
			$table->foreign('content_id')->references('id')->on('contents');

			$table->integer('parent_id')->unsigned()->nullable();
			$table->foreign('parent_id')->references('id')->on('comments');

			// Vote counts
			$table->integer('uv')->unsigned()->default(0);
			$table->integer('dv')->unsigned()->default(0);

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
		Schema::drop('comments');
	}

}
