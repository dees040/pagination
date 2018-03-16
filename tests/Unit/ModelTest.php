<?php

namespace dees040\Pagination\Tests\Unit;

use dees040\Pagination\Paginator;
use dees040\Pagination\Tests\TestModel;
use dees040\Pagination\Tests\PackageTestCase;
use Illuminate\Pagination\LengthAwarePaginator;

class ModelTest extends PackageTestCase
{
    /** @test */
    public function it_paginates_results_when_pagination_is_forced()
    {
        config(['pagination.force_pagination' => true]);

        $method = config('pagination.method');

        $response = TestModel::$method();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertArraySubset(
            ['data' => [
                0 => ['id' => 1, 'name' => 'Test 1', 'slug' => 'test-1', 'weight' => '5',]]
            ],
            $response->toArray()
        );
    }

    /** @test */
    public function it_paginates_results_when_pagination_page_is_given()
    {
        request()->query->set('page', 1);

        $method = config('pagination.method');

        $response = TestModel::$method();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertArraySubset(
            ['data' => [
                0 => ['id' => 1, 'name' => 'Test 1', 'slug' => 'test-1', 'weight' => '5',]]
            ],
            $response->toArray()
        );
    }

    /** @test */
    public function it_uses_pagination_of_the_model_per_page_attribute()
    {
        $this->testModel->setPerPage(30);

        $pagination = new Paginator($this->testModel->newQuery());

        $this->assertEquals(30, $pagination->getPerPage());
    }

    /** @test */
    public function it_can_paginate_many_results()
    {
        $this->buildTestModels();

        $method = config('pagination.method');

        $response = TestModel::orderBy('id')->$method();

        $this->assertCount(5, $response);
        $this->assertArraySubset([
            ['id' => 1, 'weight' => 5],
            ['id' => 2, 'weight' => 4],
            ['id' => 3, 'weight' => 3],
            ['id' => 4, 'weight' => 2],
            ['id' => 5, 'weight' => 1],
        ], $response->toArray());
    }

    /** @test */
    public function it_can_order_results()
    {
        $this->buildTestModels();

        request()->query->set('page', 1);
        request()->query->set('order_by', 'id');
        request()->query->set('sort_by', 'desc');

        $method = config('pagination.method');

        $response = TestModel::$method();

        $this->assertArraySubset(['data' => [
            ['id' => 5, 'weight' => 1],
            ['id' => 4, 'weight' => 2],
            ['id' => 3, 'weight' => 3],
            ['id' => 2, 'weight' => 4],
            ['id' => 1, 'weight' => 5],
        ]], $response->toArray());
    }
}
