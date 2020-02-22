<?php

namespace WebHappens\Traverser\Tests;

use WebHappens\Traverser\Tests\Stubs\Page;
use WebHappens\Traverser\Tests\Stubs\Post;

class IdTest extends TestCase
{
    public function test_id_is_returned()
    {
        $page = new Page('home');
        $id = $page->traverser()->id();

        $this->assertEquals('home', $id);

        $post = new Post(1);
        $id = $post->traverser()->id();

        $this->assertEquals(1, $id);
    }
}
