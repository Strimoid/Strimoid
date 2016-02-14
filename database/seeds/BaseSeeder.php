<?php

use Illuminate\Database\Seeder;

abstract class BaseSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker\Factory::create();
    }
}
