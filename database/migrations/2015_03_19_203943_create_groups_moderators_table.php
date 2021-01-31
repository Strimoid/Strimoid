<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsModeratorsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('group_moderators', function (Blueprint $table) {
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

            $table->boolean('accepted')->default(false);
            $table->enum('type', ['moderator', 'admin']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('group_moderators');
    }
}
