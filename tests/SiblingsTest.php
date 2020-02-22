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

        $this->assertEquals(collect([
            new Post(1), new Post(2),
        ]), $siblings);
    }

    public function test_siblings_and_self_returns_just_self_when_no_parent_exists()
    {
        $category = new Category(1);
        $siblingsAndSelf = $category->traverser()->siblingsAndSelf();

        $this->assertEquals(collect([
            new Category(1),
        ]), $siblingsAndSelf);
    }

    public function test_siblings_and_self_returns_just_self_when_none_exist()
    {
        $comment = new Comment(8);
        $siblingsAndSelf = $comment->traverser()->siblingsAndSelf();

        $this->assertEquals(collect([
            new Comment(8),
        ]), $siblingsAndSelf);
    }

    public function test_siblings_and_self_are_returned_when_they_exist()
    {
        $post = new Post(3);
        $siblingsAndSelf = $post->traverser()->siblingsAndSelf();

        $this->assertEquals(collect([
            new Post(1), new Post(2), new Post(3),
        ]), $siblingsAndSelf);
    }

    public function test_siblings_next_returns_null_when_none_exist()
    {
        $post = new Post(3);
        $siblingsNext = $post->traverser()->siblingsNext();

        $this->assertNull($siblingsNext);
    }

    public function test_siblings_next_returns_when_it_exists()
    {
        $post = new Post(1);
        $siblingsNext = $post->traverser()->siblingsNext();

        $this->assertEquals(new Post(2), $siblingsNext);
    }

    public function test_siblings_after_returns_empty_collection_when_none_exist()
    {
        $post = new Post(3);
        $siblingsAfter = $post->traverser()->siblingsAfter();

        $this->assertInstanceOf(Collection::class, $siblingsAfter);
        $this->assertCount(0, $siblingsAfter);
    }

    public function test_siblings_after_returns_when_they_exist()
    {
        $post = new Post(1);
        $siblingsAfter = $post->traverser()->siblingsAfter();

        $this->assertEquals(collect([
            new Post(2), new Post(3),
        ]), $siblingsAfter);
    }

    public function test_siblings_previous_returns_null_when_none_exist()
    {
        $post = new Post(1);
        $siblingsPrevious = $post->traverser()->siblingsPrevious();

        $this->assertNull($siblingsPrevious);
    }

    public function test_siblings_previous_returns_when_it_exists()
    {
        $post = new Post(2);
        $siblingsPrevious = $post->traverser()->siblingsPrevious();

        $this->assertEquals(new Post(1), $siblingsPrevious);
    }

    public function test_siblings_before_returns_empty_collection_when_none_exist()
    {
        $post = new Post(1);
        $siblingsBefore = $post->traverser()->siblingsBefore();

        $this->assertInstanceOf(Collection::class, $siblingsBefore);
        $this->assertCount(0, $siblingsBefore);
    }

    public function test_siblings_before_returns_when_they_exist()
    {
        $post = new Post(3);
        $siblingsBefore = $post->traverser()->siblingsBefore();

        $this->assertEquals(collect([
            new Post(1), new Post(2),
        ]), $siblingsBefore);
    }

    public function test_siblings_position()
    {
        $this->assertEquals(0, (new Category(2))->traverser()->siblingsPosition());
        $this->assertEquals(0, (new Post(1))->traverser()->siblingsPosition());
        $this->assertEquals(1, (new Post(2))->traverser()->siblingsPosition());
        $this->assertEquals(2, (new Post(3))->traverser()->siblingsPosition());
    }
}
