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
        $this->assertEquals(collect(), $traverser->relations());
    }

    public function test_construct_with_current_and_relations()
    {
        $current = new stdClass;
        $relations = ['foo' => 'bar'];
        $traverser = Traverser::make($current, $relations);

        $this->assertEquals($current, $traverser->current());
        $this->assertEquals(collect($relations), $traverser->relations());
    }

    public function test_current_getter_setter()
    {
        $current = new stdClass;
        $traverser = Traverser::make()->current($current);

        $this->assertEquals($current, $traverser->current());
    }

    public function test_relations_getter_setter()
    {
        $relations = ['foo' => 'bar'];
        $traverser = Traverser::make()->relations($relations);

        $this->assertEquals(collect($relations), $traverser->relations());
    }
}
