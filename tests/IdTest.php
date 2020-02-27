<?php

namespace WebHappens\Traverser\Tests;

use stdClass;
use WebHappens\Traverser\Traverser;
use WebHappens\Traverser\Tests\Stubs\Page;
use WebHappens\Traverser\Tests\Stubs\Post;

class IdTest extends TestCase
{
    /** @test */
    public function id_returns_null_when_no_current()
    {
        $id = Traverser::make()->id();

        $this->assertNull($id);
    }

    /** @test */
    public function id_returns_null_when_not_set()
    {
        $object = new stdClass;

        $this->assertNull(Traverser::make($object)->id());
    }

    /** @test */
    public function id_is_returned()
    {
        $page = new Page('home');
        $id = $page->traverser()->id();

        $this->assertEquals('home', $id);

        $post = new Post(1);
        $id = $post->traverser()->id();

        $this->assertEquals(1, $id);
    }
}
