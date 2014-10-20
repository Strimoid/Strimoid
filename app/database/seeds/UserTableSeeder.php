<?php

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::collection('users')->delete();

        // Insert 50 elements filled with random data
        $faker = \Faker\Factory::create();

        for ($x = 0; $x < 50; $x++)
        {
            User::create([
                'created_at' => $faker->dateTimeThisDecade,
                'name' => $faker->userName,
                'email' => $faker->email,
                'password' => $faker->password,
                'is_activated' => true,
            ]);
        }
    }

}
