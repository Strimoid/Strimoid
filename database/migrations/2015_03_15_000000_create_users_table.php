<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            // Credentials
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();

            // Some internal info
            $table->enum('type', ['user', 'banned', 'admin'])->default('user');
            $table->string('last_ip', 45);
            $table->boolean('is_activated')->default(false);
            $table->string('activation_token', 16)->nullable();
            $table->integer('total_points')->default(0);

            // User profile
            $table->string('avatar', 16)->nullable();
            $table->smallInteger('age')->nullable()->unsigned();
            $table->enum('sex', ['unknown', 'male', 'female'])->default('unknown');
            $table->string('location')->nullable();
            $table->text('description', 255)->nullable();

            // Dates
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('last_login')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('users');
    }
}
