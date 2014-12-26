<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Setup extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments');
		Schema::create('contents');
		Schema::create('conversations');
		Schema::create('entries');
		Schema::create('groups');
		Schema::create('notifications');
		Schema::create('users');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments');
		Schema::drop('contents');
		Schema::drop('conversations');
		Schema::drop('entries');
		Schema::drop('groups');
		Schema::drop('notifications');
		Schema::drop('users');
	}

}
