<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contents', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('title');
			$table->string('description')->nullable();
			$table->string('domain');
			$table->string('thumbnail')->nullable();

			$table->string('url')->nullable();
			$table->text('text')->nullable();

			// Flags
			$table->boolean('eng');
			$table->boolean('nsfw');

			// Vote counts
			$table->integer('uv')->unsigned()->default(0);
			$table->integer('dv')->unsigned()->default(0);

			// Relations
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');

			$table->integer('group_id')->unsigned();
			$table->foreign('group_id')->references('id')->on('groups');

			// Counters
			$table->integer('comments_count')->unsigned()->default(0);
			$table->integer('related_count')->unsigned()->default(0);

			// Timestamps
			$this->timestamp('frontpage_at')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contents');
	}

}
