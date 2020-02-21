<?php

namespace WebHappens\Traverser\Tests;

use Illuminate\Support\Collection;
use WebHappens\Traverser\Tests\Stubs\Post;
use WebHappens\Traverser\Tests\Stubs\Comment;
use WebHappens\Traverser\Tests\Stubs\Category;

class PostsTest extends TestCase
{
    public function test_parent_returns_null_when_none_exist()
    {
        $category = new Category(1);
        $parent = $category->traverser()->parent();

        $this->assertNull($parent);
    }

    public function test_parent_is_returned_when_it_exists()
    {
        $post = new Post(1);
        $parent = $post->traverser()->parent();

        $this->assertEquals(new Category(2), $parent);
    }

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
            new Category(2),
            new Post(2)
        ]), $ancestors);
    }

    public function test_ancestors_and_self_returns_just_self_when_no_ancestors_exist()
    {
        $category = new Category(1);
        $ancestorsAndSelf = $category->traverser()->ancestorsAndSelf();

        $this->assertEquals(collect([new Category(1)]), $ancestorsAndSelf);
    }

    public function test_ancestors_and_self_are_returned_when_they_exist()
    {
        $comment = new Comment(3);
        $ancestorsAndSelf = $comment->traverser()->ancestorsAndSelf();

        $this->assertEquals(collect([
            new Category(2),
            new Post(2),
            new Comment(3)
        ]), $ancestorsAndSelf);
    }

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
