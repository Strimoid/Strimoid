<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBlockedUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_blocked_users', function (Blueprint $table) {
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
     */
    public function down(): void
    {
        Schema::drop('user_blocked_users');
    }
}
