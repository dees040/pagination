<?php

namespace dees040\Pagination\Tests;

use Dotenv\Dotenv;
use Orchestra\Testbench\TestCase;
use dees040\Pagination\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;

abstract class PackageTestCase extends TestCase
{
    /**
     * The TestModel.
     *
     * @var \dees040\Pagination\Tests\TestModel
     */
    protected $testModel;

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        $this->loadEnvironmentVariables();

        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    /**
     * Load the environment variables (file).
     *
     * @return void
     */
    protected function loadEnvironmentVariables()
    {
        if (! file_exists(__DIR__.'/../.env')) {
            return;
        }

        $dotenv = new Dotenv(__DIR__.'/..');
        $dotenv->load();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Set up the database.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('weight');
            $table->timestamps();
        });

        $this->testModel = TestModel::create([
            'name' => 'Test 1',
            'slug' => 'test-1',
            'weight' => 5,
        ]);
    }

    /**
     * Build 4 other test models.
     *
     * @return void
     */
    protected function buildTestModels()
    {
        $data = [[2, 4], [3, 3], [4, 2], [5, 1]];

        foreach ($data as $item) {
            TestModel::create([
                'name' => 'Test '.head($item),
                'slug' => 'test-'.head($item),
                'weight' => last($item),
            ]);
        }
    }
}
