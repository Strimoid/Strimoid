<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

    /**
     * @var
     *
     */
    protected $faker;

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        return require __DIR__.'/../../bootstrap/start.php';
    }

    /**
     * Seed database with fake data.
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->seed();
        $this->faker = \Faker\Factory::create();;
    }

    /**
     * Return random _id from given collection.
     *
     */
    protected function randomId($collection)
    {
        return current(DB::collection($collection)->take(1)->lists('_id'));
    }

}
