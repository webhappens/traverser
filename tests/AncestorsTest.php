<?php

namespace WebHappens\Traverser\Tests;

use Illuminate\Support\Collection;
use WebHappens\Traverser\Tests\Stubs\Post;
use WebHappens\Traverser\Tests\Stubs\Comment;
use WebHappens\Traverser\Tests\Stubs\Category;

class AncestorsTest extends TestCase
{
    public function test_ancestors_returns_empty_collection_when_none_exist()
    {
        $category = new Category(1);
        $ancestors = $category->traverser()->ancestors();

        $this->assertInstanceOf(Collection::class, $ancestors);
        $this->assertEmpty($ancestors);
    }

    public function test_ancestors_are_returned_when_they_exist()
    {
        $comment = new Comment(3);
        $ancestors = $comment->traverser()->ancestors();

        $this->assertEquals(collect([
            new Category(2), new Post(2),
        ]), $ancestors);
    }

    public function test_ancestors_and_self_returns_just_self_when_no_ancestors_exist()
    {
        $category = new Category(1);
        $ancestorsAndSelf = $category->traverser()->ancestorsAndSelf();

        $this->assertEquals(collect([
            new Category(1),
        ]), $ancestorsAndSelf);
    }

    public function test_ancestors_and_self_are_returned_when_they_exist()
    {
        $comment = new Comment(3);
        $ancestorsAndSelf = $comment->traverser()->ancestorsAndSelf();

        $this->assertEquals(collect([
            new Category(2), new Post(2), new Comment(3),
        ]), $ancestorsAndSelf);
    }
}
