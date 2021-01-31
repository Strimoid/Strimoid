<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');

            // Basic data
            $table->string('avatar', 16)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('name')->unique();
            $table->string('style')->nullable();
            $table->string('urlname');
            $table->enum('type', ['public', 'private']);
            $table->integer('popular_threshold')->nullable();

            // Sidebar text
            $table->text('sidebar')->nullable();
            $table->text('sidebar_source')->nullable();

            // Relations
            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on('users');

            // Counters
            $table->integer('subscribers_count')->unsigned()->default(0);

            // Dates
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
}
