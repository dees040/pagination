<?php

namespace dees040\Pagination\Tests\Unit;

use dees040\Pagination\Tests\TestModel;
use dees040\Pagination\Tests\PackageTestCase;

class ConfigTest extends PackageTestCase
{
    /** @test */
    public function it_has_a_method_for_pagination()
    {
        $method = config('pagination.method');

        $this->assertTrue(is_callable(TestModel::class, $method));
    }
}
