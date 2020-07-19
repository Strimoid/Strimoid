<?php

use Illuminate\Database\Seeder;
use Strimoid\Models\Group;
use Strimoid\Models\User;

class MockSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name'         => 'admin',
            'email'        => 'admin@strm.local',
            'password'     => 'admin',
            'is_activated' => true,
            'last_ip'      => '127.0.0.1',
            'type'         => 'admin',
        ]);

        $user = User::create([
            'name'         => 'user',
            'email'        => 'user@strm.local',
            'password'     => 'user',
            'is_activated' => true,
            'last_ip'      => '127.0.0.1',
        ]);

        Group::create([
            'name'        => 'Ducks',
            'urlname'     => 'ducks',
            'description' => 'Everything about ducks',
            'creator_id'  => $user->id,
        ]);
    }
}
