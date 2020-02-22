<?php

namespace WebHappens\Traverser\Tests;

use WebHappens\Traverser\Tests\Stubs\Page;
use WebHappens\Traverser\Tests\Stubs\Post;
use WebHappens\Traverser\Tests\Stubs\Category;

class ParentTest extends TestCase
{
    public function test_parent_returns_null_when_none_exist()
    {
        $page = new Page('home');
        $parent = $page->traverser()->parent();

        $this->assertNull($parent);

        $category = new Category(1);
        $parent = $category->traverser()->parent();

        $this->assertNull($parent);
    }

    public function test_parent_is_returned_when_it_exists()
    {
        $page = new Page('about');
        $parent = $page->traverser()->parent();

        $this->assertEquals(new Page('home'), $parent);

        $post = new Post(1);
        $parent = $post->traverser()->parent();

        $this->assertEquals(new Category(2), $parent);
    }
}
