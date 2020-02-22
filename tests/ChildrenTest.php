<?php

namespace WebHappens\Traverser\Tests;

use Illuminate\Support\Collection;
use WebHappens\Traverser\Tests\Stubs\Post;
use WebHappens\Traverser\Tests\Stubs\Category;

class ChildrenTest extends TestCase
{
    public function test_children_returns_empty_collection_when_none_exist()
    {
        $category = new Category(1);
        $children = $category->traverser()->children();

        $this->assertInstanceOf(Collection::class, $children);
        $this->assertEmpty($children);
    }

    public function test_children_are_returned_when_they_exist()
    {
        $category = new Category(2);
        $children = $category->traverser()->children();

        $this->assertInstanceOf(Collection::class, $children);
        $this->assertCount(3, $children);
        $this->assertContainsOnlyInstancesOf(Post::class, $children);
    }
}
