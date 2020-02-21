<?php

namespace WebHappens\Traverser\Tests;

use Illuminate\Support\Collection;
use WebHappens\Traverser\Tests\Stubs\Page;

class PagesTest extends TestCase
{
    public function test_parent_returns_null_when_none_exist()
    {
        $page = new Page(1);
        $parent = $page->traverser()->parent();

        $this->assertNull($parent);
    }

    public function test_parent_is_returned_when_it_exists()
    {
        $post = new Page(2);
        $parent = $post->traverser()->parent();

        $this->assertEquals(new Page(1), $parent);
    }

    public function test_children_returns_empty_collection_when_none_exist()
    {
        $page = new Page(2);
        $children = $page->traverser()->children();

        $this->assertInstanceOf(Collection::class, $children);
        $this->assertEmpty($children);
    }

    public function test_children_are_returned_when_they_exist()
    {
        $page = new Page(1);
        $children = $page->traverser()->children();

        $this->assertInstanceOf(Collection::class, $children);
        $this->assertCount(2, $children);
        $this->assertContainsOnlyInstancesOf(Page::class, $children);
    }
}
