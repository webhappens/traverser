<?php

namespace WebHappens\Traverser\Tests;

use stdClass;
use WebHappens\Traverser\Traverser;

class ResolveMappingTest extends TestCase
{
    public function test_default_maps()
    {
        $this->assertEquals(
            ['id' => 'id', 'parent' => 'parent', 'children' => 'children'],
            Traverser::$defaultMaps
        );
    }

    public function test_maps_resolve_to_methods_first()
    {
        $traverser = Traverser::make($this->getMockObjectWithMethodsAndProperties());

        $this->assertEquals(new stdClass, $traverser->parent());
        $this->assertEquals(collect(['foo']), $traverser->children());
        $this->assertEquals('id', $traverser->id());
    }

    public function test_maps_resolve_to_properties()
    {
        $traverser = Traverser::make($this->getMockObjectWithJustProperties());

        $this->assertEquals(new stdClass, $traverser->parent());
        $this->assertEquals(collect(['foo']), $traverser->children());
        $this->assertEquals('id', $traverser->id());
    }

    private function getMockObjectWithMethodsAndProperties()
    {
        return new class ()
        {
            public $parent, $children, $id;

            public function parent()
            {
                return new stdClass;
            }

            public function children()
            {
                return collect(['foo']);
            }

            public function id()
            {
                return 'id';
            }
        };
    }

    private function getMockObjectWithJustProperties()
    {
        $object = new class () {
            public $parent, $children, $id;
        };

        $object->parent = new stdClass();
        $object->children = collect(['foo']);
        $object->id = 'id';

        return $object;
    }
}
