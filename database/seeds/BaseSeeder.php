<?php

use Illuminate\Database\Seeder;

class BaseSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker\Factory::create();
    }
}
