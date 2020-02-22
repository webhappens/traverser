<?php

namespace WebHappens\Traverser\Tests;

use WebHappens\Traverser\Traverser;
use WebHappens\Traverser\Tests\Stubs\Page;
use WebHappens\Traverser\Tests\Stubs\Post;
use WebHappens\Traverser\Tests\Stubs\Comment;
use Orchestra\Testbench\TestCase as Orchestra;
use WebHappens\Traverser\Tests\Stubs\Category;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind('traverser', function () {
            return Traverser::make()->relations([
                Page::class => ['id' => 'uri'],
                Category::class => ['children' => 'posts'],
                Post::class => ['parent' => 'category', 'children' => 'comments'],
                Comment::class => ['parent' => 'post'],
            ]);
        });
    }
}
