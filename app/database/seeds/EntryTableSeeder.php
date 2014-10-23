<?php

class EntryTableSeeder extends Seeder {

    public function run()
    {
        DB::collection('entries')->delete();

        // Get list of user and group ids
        $groupIds = DB::collection('groups')->lists('_id');
        $userIds = DB::collection('users')->lists('_id');

        // Insert 50 elements filled with random data
        $faker = \Faker\Factory::create();

        for ($x = 0; $x < 50; $x++)
        {
            $randomUser = array_rand($userIds);
            $randomGroup = array_rand($groupIds);

            Content::create([
                'created_at' => $faker->dateTimeThisDecade,
                'group_id' => $randomGroup,
                'text' => $faker->text(512),
                'user_id' => $randomUser,
            ]);
        }
    }

}
