<?php

namespace WebHappens\Traverser\Tests\Stubs;

class Category extends Model
{
    protected static $data = [
        1 => [],
        2 => ['posts' => [1, 2, 3]],
        3 => ['posts' => [4, 5]],
    ];

    public function posts()
    {
        return collect(static::data($this->id . '.posts'))
            ->mapInto(Post::class)
            ->toArray();
    }
}
