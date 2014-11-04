<?php

class GroupTableSeeder extends Seeder {

    public function run()
    {
        DB::collection('groups')->delete();

        // Load user ids, we will need them later
        $userIds = DB::collection('users')->lists('_id');

        // Insert 50 elements filled with random data
        $faker = \Faker\Factory::create();

        for ($x = 0; $x < 50; $x++)
        {
            $randomUser = array_rand($userIds);

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
