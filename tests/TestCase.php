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
        $app = require __DIR__.'/../bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app;
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
     * Return urlName of random group.
     *
     */
    protected function randomGroup()
    {
        return $this->randomField('groups', 'urlname');
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
