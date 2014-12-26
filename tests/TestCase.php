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
    protected function randomId($table)
    {
        return $this->randomField($table, 'id');
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
    protected function randomField($table, $field)
    {
        $row = DB::table($table)->take(1)->lists($field);
        return current($row);
    }
}
