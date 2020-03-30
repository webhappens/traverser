<?php

namespace WebHappens\Traverser\Tests;

use Illuminate\Support\Collection;
use WebHappens\Traverser\Traverser;
use WebHappens\Traverser\Tests\Stubs\Post;
use WebHappens\Traverser\Tests\Stubs\Comment;
use WebHappens\Traverser\Tests\Stubs\Category;

class SiblingsTest extends TestCase
{
    /** @test */
    public function siblings_returns_empty_collection_when_no_current()
    {
        $siblings = Traverser::make()->siblings();

        $this->assertInstanceOf(Collection::class, $siblings);
        $this->assertEmpty($siblings);
    }

    /** @test */
    public function siblings_returns_empty_collection_when_no_parent_exists()
    {
        $category = new Category(1);
        $siblings = $category->traverser()->siblings();

        $this->assertInstanceOf(Collection::class, $siblings);
        $this->assertEmpty($siblings);
    }

    /** @test */
    public function siblings_returns_empty_collection_when_none_exist()
    {
        $comment = new Comment(8);
        $siblings = $comment->traverser()->siblings();

        $this->assertInstanceOf(Collection::class, $siblings);
        $this->assertEmpty($siblings);
    }

    /** @test */
    public function siblings_are_returned_when_they_exist()
    {
        $post = new Post(3);
        $siblings = $post->traverser()->siblings();

        $this->assertEquals(collect([
            new Category(3), new Post(1), new Post(2),
        ]), $siblings);
    }

    /** @test */
    public function siblings_and_self_returns_empty_collection_when_no_current()
    {
        $siblingsAndSelf = Traverser::make()->siblingsAndSelf();

        $this->assertInstanceOf(Collection::class, $siblingsAndSelf);
        $this->assertEmpty($siblingsAndSelf);
    }

    /** @test */
    public function siblings_and_self_returns_just_self_when_no_parent_exists()
    {
        $category = new Category(1);
        $siblingsAndSelf = $category->traverser()->siblingsAndSelf();

        $this->assertEquals(collect([
            new Category(1),
        ]), $siblingsAndSelf);
    }

    /** @test */
    public function siblings_and_self_returns_just_self_when_none_exist()
    {
        $comment = new Comment(8);
        $siblingsAndSelf = $comment->traverser()->siblingsAndSelf();

        $this->assertEquals(collect([
            new Comment(8),
        ]), $siblingsAndSelf);
    }

    /** @test */
    public function siblings_and_self_are_returned_when_they_exist()
    {
        $post = new Post(3);
        $siblingsAndSelf = $post->traverser()->siblingsAndSelf();

        $this->assertEquals(collect([
            new Category(3), new Post(1), new Post(2), new Post(3),
        ]), $siblingsAndSelf);
    }

    /** @test */
    public function siblings_next_returns_null_when_no_current()
    {
        $siblingsNext = Traverser::make()->siblingsNext();

        $this->assertNull($siblingsNext);
    }

    /** @test */
    public function siblings_next_returns_null_when_none_exist()
    {
        $post = new Post(3);
        $siblingsNext = $post->traverser()->siblingsNext();

        $this->assertNull($siblingsNext);
    }

    /** @test */
    public function siblings_next_returns_when_it_exists()
    {
        $post = new Post(1);
        $siblingsNext = $post->traverser()->siblingsNext();

        $this->assertEquals(new Post(2), $siblingsNext);
    }

    /** @test */
    public function siblings_after_returns_empty_collection_when_no_current()
    {
        $siblingsAfter = Traverser::make()->siblingsAfter();

        $this->assertInstanceOf(Collection::class, $siblingsAfter);
        $this->assertEmpty($siblingsAfter);
    }

    /** @test */
    public function siblings_after_returns_empty_collection_when_none_exist()
    {
        $post = new Post(3);
        $siblingsAfter = $post->traverser()->siblingsAfter();

        $this->assertInstanceOf(Collection::class, $siblingsAfter);
        $this->assertCount(0, $siblingsAfter);
    }

    /** @test */
    public function siblings_after_returns_when_they_exist()
    {
        $post = new Post(1);
        $siblingsAfter = $post->traverser()->siblingsAfter();

        $this->assertEquals(collect([
            new Post(2), new Post(3),
        ]), $siblingsAfter);
    }

    /** @test */
    public function siblings_previous_returns_null_when_no_current()
    {
        $siblingsPrevious = Traverser::make()->siblingsPrevious();

        $this->assertNull($siblingsPrevious);
    }

    /** @test */
    public function siblings_previous_returns_null_when_none_exist()
    {
        $post = new Post(4);
        $siblingsPrevious = $post->traverser()->siblingsPrevious();

        $this->assertNull($siblingsPrevious);
    }

    /** @test */
    public function siblings_previous_returns_when_it_exists()
    {
        $post = new Post(2);
        $siblingsPrevious = $post->traverser()->siblingsPrevious();

        $this->assertEquals(new Post(1), $siblingsPrevious);
    }

    /** @test */
    public function siblings_before_returns_empty_collection_when_no_current()
    {
        $siblingsBefore = Traverser::make()->siblingsBefore();

        $this->assertInstanceOf(Collection::class, $siblingsBefore);
        $this->assertEmpty($siblingsBefore);
    }

    /** @test */
    public function siblings_before_returns_empty_collection_when_none_exist()
    {
        $post = new Post(4);
        $siblingsBefore = $post->traverser()->siblingsBefore();

        $this->assertInstanceOf(Collection::class, $siblingsBefore);
        $this->assertCount(0, $siblingsBefore);
    }

    /** @test */
    public function siblings_before_returns_when_they_exist()
    {
        $post = new Post(3);
        $siblingsBefore = $post->traverser()->siblingsBefore();

        $this->assertEquals(collect([
            new Category(3), new Post(1), new Post(2),
        ]), $siblingsBefore);
    }

    /** @test */
    public function siblings_position_returns_null_when_no_current()
    {
        $siblingsPosition = Traverser::make()->siblingsPosition();

        $this->assertNull($siblingsPosition);
    }

    /** @test */
    public function siblings_position()
    {
        $this->assertEquals(0, (new Category(2))->traverser()->siblingsPosition());
        $this->assertEquals(0, (new Category(3))->traverser()->siblingsPosition());
        $this->assertEquals(1, (new Post(1))->traverser()->siblingsPosition());
        $this->assertEquals(2, (new Post(2))->traverser()->siblingsPosition());
        $this->assertEquals(3, (new Post(3))->traverser()->siblingsPosition());
    }
}
