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
     * Return _id of random element from given collection.
     *
     */
    protected function randomId($collection)
    {
        return $this->randomField($collection, '_id');
    }

    /**
     * Return field of random element from given collection.
     *
     */
    protected function randomField($collection, $field)
    {
        return current(DB::collection($collection)->take(1)->lists($field));
    }
}
