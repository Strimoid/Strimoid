<?php

use Illuminate\Database\Migrations\Migration;

class setup extends Migration
{
    private $tables = [
        'comments',
        'contents',
        'conversations',
        'entries',
        'groups',
        'notifications',
        'users',
        'user_actions',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $table) {
            Schema::create($table);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $table) {
            Schema::drop($table);
        }
    }
}
