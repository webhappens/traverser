<?php

namespace WebHappens\Traverser\Tests\Stubs;

class Comment extends Model
{
    protected static $data = [
        1 => [],
        2 => [],
        3 => [],
        4 => [],
        5 => [],
        6 => [],
        7 => [],
        8 => [],
    ];

    public function post()
    {
        return $this->traverser()->inferParent(static::everything());
    }
}
