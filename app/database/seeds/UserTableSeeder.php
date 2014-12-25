<?php

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        // Insert 50 elements filled with random data
        $faker = \Faker\Factory::create();

        foreach (range(1, 50) as $x)
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
