<?php

namespace WebHappens\Traverser\Tests;

use stdClass;
use WebHappens\Traverser\Traverser;

class ConstructTest extends TestCase
{
    public function test_construct()
    {
        $traverser = Traverser::make();

        $this->assertEquals(null, $traverser->current());
        $this->assertEquals(collect(), $traverser->maps());
    }

    public function test_construct_with_current_and_maps()
    {
        $current = new stdClass;
        $maps = ['foo' => ['parent' => 'bar']];
        $traverser = Traverser::make($current, $maps);

        $this->assertEquals($current, $traverser->current());
        $this->assertEquals(collect($maps), $traverser->maps());
    }

    public function test_current_getter_setter()
    {
        $current = new stdClass;
        $traverser = Traverser::make()->current($current);

        $this->assertEquals($current, $traverser->current());
    }

    public function test_maps_getter_setter()
    {
        $maps = ['foo' => ['parent' => 'bar']];
        $traverser = Traverser::make()->maps($maps);

        $this->assertEquals(collect($maps), $traverser->maps());
    }
}
