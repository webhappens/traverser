<?php

namespace WebHappens\Traverser\Tests;

use Illuminate\Support\Collection;
use WebHappens\Traverser\Tests\Stubs\Page;
use WebHappens\Traverser\Tests\Stubs\Post;
use WebHappens\Traverser\Tests\Stubs\Category;

class ChildrenTest extends TestCase
{
    public function test_children_returns_empty_collection_when_none_exist()
    {
        $page = new Page('about');
        $children = $page->traverser()->children();

        $this->assertInstanceOf(Collection::class, $children);
        $this->assertEmpty($children);

        $category = new Category(1);
        $children = $category->traverser()->children();

        $this->assertInstanceOf(Collection::class, $children);
        $this->assertEmpty($children);
    }

    public function test_children_are_returned_when_they_exist()
    {
        $page = new Page('home');
        $children = $page->traverser()->children();

        $this->assertEquals(collect([
            new Page('about'), new Page('contact'),
        ]), $children);

        $category = new Category(2);
        $children = $category->traverser()->children();

        $this->assertEquals(collect([
            new Category(3), new Post(1), new Post(2), new Post(3),
        ]), $children);
    }
}
