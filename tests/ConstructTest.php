<?php

namespace WebHappens\Traverser\Tests;

use stdClass;
use WebHappens\Traverser\Traverser;

class ConstructTest extends TestCase
{
    /** @test */
    public function construct()
    {
        $traverser = Traverser::make();

        $this->assertEquals(null, $traverser->current());
        $this->assertEquals(collect(), $traverser->maps());
    }

    /** @test */
    public function construct_with_current_and_maps()
    {
        $current = new stdClass;
        $maps = ['foo' => ['parent' => 'bar']];
        $traverser = Traverser::make($current, $maps);

        $this->assertEquals($current, $traverser->current());
        $this->assertEquals(collect($maps), $traverser->maps());
    }

    /** @test */
    public function current_getter_setter()
    {
        $current = new stdClass;
        $traverser = Traverser::make()->current($current);

        $this->assertEquals($current, $traverser->current());
    }

    /** @test */
    public function maps_getter_setter()
    {
        $maps = ['foo' => ['parent' => 'bar']];
        $traverser = Traverser::make()->maps($maps);

        $this->assertEquals(collect($maps), $traverser->maps());
    }
}
