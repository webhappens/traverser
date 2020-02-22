<?php

namespace WebHappens\Traverser\Tests;

use Illuminate\Support\Collection;
use WebHappens\Traverser\Tests\Stubs\Post;
use WebHappens\Traverser\Tests\Stubs\Comment;
use WebHappens\Traverser\Tests\Stubs\Category;

class SiblingsTest extends TestCase
{
    public function test_siblings_returns_empty_collection_when_no_parent_exists()
    {
        $category = new Category(1);
        $siblings = $category->traverser()->siblings();

        $this->assertInstanceOf(Collection::class, $siblings);
        $this->assertEmpty($siblings);
    }

    public function test_siblings_returns_empty_collection_when_none_exist()
    {
        $comment = new Comment(8);
        $siblings = $comment->traverser()->siblings();

        $this->assertInstanceOf(Collection::class, $siblings);
        $this->assertEmpty($siblings);
    }

    public function test_siblings_are_returned_when_they_exist()
    {
        $post = new Post(3);
        $siblings = $post->traverser()->siblings();

        $this->assertEquals(collect([new Post(1), new Post(2)]), $siblings);
    }

    public function test_siblings_and_self_returns_just_self_when_no_parent_exists()
    {
        $category = new Category(1);
        $siblingsAndSelf = $category->traverser()->siblingsAndSelf();

        $this->assertEquals(collect([new Category(1)]), $siblingsAndSelf);
    }

    public function test_siblings_and_self_returns_just_self_when_none_exist()
    {
        $comment = new Comment(8);
        $siblingsAndSelf = $comment->traverser()->siblingsAndSelf();

        $this->assertEquals(collect([new Comment(8)]), $siblingsAndSelf);
    }

    public function test_siblings_and_self_are_returned_when_they_exist()
    {
        $post = new Post(3);
        $siblingsAndSelf = $post->traverser()->siblingsAndSelf();

        $this->assertEquals(collect([new Post(1), new Post(2), new Post(3)]), $siblingsAndSelf);
    }
}
