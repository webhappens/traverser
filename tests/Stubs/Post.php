<?php

namespace WebHappens\Traverser\Tests\Stubs;

class Post extends Model
{
    protected static $data = [
        1 => ['comments' => [1, 2]],
        2 => ['comments' => [3]],
        3 => ['comments' => [4, 5, 6, 7]],
        4 => [],
        5 => ['comments' => [8]],
    ];

    public function category()
    {
        return $this->traverser()->inferParent(static::everything());
    }

    public function comments()
    {
        return collect(static::data($this->id . '.comments'))->mapInto(Comment::class);
    }
}
