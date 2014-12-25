<?php

use Strimoid\Models\Group;

class GroupTableSeeder extends BaseSeeder {

    public function run()
    {
        DB::table('groups')->delete();

        // Load user ids, we will need them later
        $userIds = DB::table('users')->lists('id');

        // Insert 50 elements filled with random data
        $faker = \Faker\Factory::create();

        foreach (range(1, 50) as $x)
        {
            $randomUser = (string) $userIds[array_rand($userIds)];

            Group::create([
                'created_at' => $faker->dateTimeThisDecade,
                'creator_id' => $randomUser,
                'name' => $faker->city,
                'description' => implode(' ', $faker->sentences(2)),
                'sidebar' => $faker->paragraph,
                'tags' => $faker->words(5),
                'urlname' => $faker->domainWord,
            ]);
        }
    }

}
