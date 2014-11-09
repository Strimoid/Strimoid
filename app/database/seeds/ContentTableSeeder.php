<?php

class ContentTableSeeder extends Seeder {

    public function run()
    {
        DB::collection('contents')->delete();

        // Get list of user and group ids
        $groupIds = DB::collection('groups')->lists('_id');
        $userIds = DB::collection('users')->lists('_id');

        // Insert 50 elements filled with random data
        $faker = \Faker\Factory::create();

        for ($x = 0; $x < 50; $x++)
        {
            $randomUser = (string) $userIds[array_rand($userIds)];
            $randomGroup = (string) $groupIds[array_rand($groupIds)];

            Content::create([
                'created_at' => $faker->dateTimeThisDecade,
                'group_id' => $randomGroup,
                'title' => $faker->sentence(10),
                'description' => $faker->text(200),
                'user_id' => $randomUser,
                'url' => $faker->url,
            ]);
        }
    }

}
