<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentRelatedTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('content_related', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->text('url');
            $table->string('thumbnail')->nullable();

            $table->integer('content_id')->unsigned();
            $table->foreign('content_id')
                ->references('id')->on('contents')
                ->onDelete('cascade');

            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')
                ->references('id')->on('groups')
                ->onDelete('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            // Flags
            $table->boolean('eng')->default(0);
            $table->boolean('nsfw')->default(0);

            // Vote counts
            $table->integer('uv')->unsigned()->default(0);
            $table->integer('dv')->unsigned()->default(0);
            $table->integer('score')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('content_related');
    }
}
