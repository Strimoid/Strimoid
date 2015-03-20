<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFolderGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('folder_groups', function(Blueprint $table)
		{
			$table->integer('folder_id')->unsigned();
			$table->foreign('folder_id')
				->references('id')->on('folders')
				->onDelete('cascade');

			$table->integer('group_id')->unsigned();
			$table->foreign('group_id')
				->references('id')->on('groups')
				->onDelete('cascade');

			$table->timestamp('created_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('folder_groups');
	}

}
