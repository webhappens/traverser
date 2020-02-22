<?php

namespace WebHappens\Traverser\Tests;

use Illuminate\Support\Collection;
use WebHappens\Traverser\Tests\Stubs\Post;
use WebHappens\Traverser\Tests\Stubs\Comment;
use WebHappens\Traverser\Tests\Stubs\Category;

class DescendantsTest extends TestCase
{
    public function test_descendants_returns_empty_collection_when_none_exist()
    {
        $category = new Category(1);
        $descendants = $category->traverser()->descendants();

        $this->assertInstanceOf(Collection::class, $descendants);
        $this->assertEmpty($descendants);
    }

    public function test_descendants_are_returned_when_they_exist()
    {
        $category = new Category(2);
        $descendants = $category->traverser()->descendants();

        $this->assertEquals(collect([
            new Post(1), new Comment(1), new Comment(2),
            new Post(2), new Comment(3),
            new Post(3), new Comment(4), new Comment(5), new Comment(6), new Comment(7),
        ]), $descendants);
    }

    public function test_descendants_and_self_returns_just_self_when_no_descendants_exist()
    {
        $comment = new Comment(1);
        $descendantsAndSelf = $comment->traverser()->descendantsAndSelf();

        $this->assertEquals(collect([new Comment(1)]), $descendantsAndSelf);
    }

    public function test_descendants_and_self_are_returned_when_they_exist()
    {
        $category = new Category(2);
        $descendants = $category->traverser()->descendantsAndSelf();

        $this->assertEquals(collect([
            new Category(2),
            new Post(1), new Comment(1), new Comment(2),
            new Post(2), new Comment(3),
            new Post(3), new Comment(4), new Comment(5), new Comment(6), new Comment(7),
        ]), $descendants);
    }
}
