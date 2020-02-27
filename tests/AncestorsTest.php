<?php

namespace WebHappens\Traverser\Tests;

use WebHappens\Traverser\Traverser;
use Tightenco\Collect\Support\Collection;
use WebHappens\Traverser\Tests\Stubs\Post;
use WebHappens\Traverser\Tests\Stubs\Comment;
use WebHappens\Traverser\Tests\Stubs\Category;

class AncestorsTest extends TestCase
{
    /** @test */
    public function ancestors_returns_empty_collection_when_no_current()
    {
        $ancestors = Traverser::make()->ancestors();

        $this->assertInstanceOf(Collection::class, $ancestors);
        $this->assertEmpty($ancestors);
    }

    /** @test */
    public function ancestors_returns_empty_collection_when_none_exist()
    {
        $category = new Category(1);
        $ancestors = $category->traverser()->ancestors();

        $this->assertInstanceOf(Collection::class, $ancestors);
        $this->assertEmpty($ancestors);
    }

    /** @test */
    public function ancestors_are_returned_when_they_exist()
    {
        $comment = new Comment(3);
        $ancestors = $comment->traverser()->ancestors();

        $this->assertEquals(collect([
            new Category(2), new Post(2),
        ]), $ancestors);
    }

    /** @test */
    public function ancestors_and_self_returns_empty_collection_when_no_current()
    {
        $ancestorsAndSelf = Traverser::make()->ancestorsAndSelf();

        $this->assertInstanceOf(Collection::class, $ancestorsAndSelf);
        $this->assertEmpty($ancestorsAndSelf);
    }

    /** @test */
    public function ancestors_and_self_returns_just_self_when_no_ancestors_exist()
    {
        $category = new Category(1);
        $ancestorsAndSelf = $category->traverser()->ancestorsAndSelf();

        $this->assertEquals(collect([
            new Category(1),
        ]), $ancestorsAndSelf);
    }

    /** @test */
    public function ancestors_and_self_are_returned_when_they_exist()
    {
        $comment = new Comment(3);
        $ancestorsAndSelf = $comment->traverser()->ancestorsAndSelf();

        $this->assertEquals(collect([
            new Category(2), new Post(2), new Comment(3),
        ]), $ancestorsAndSelf);
    }
}
