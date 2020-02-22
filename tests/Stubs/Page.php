<?php

namespace WebHappens\Traverser\Tests\Stubs;

class Page extends Model
{
    protected static $data = [
        1 => [],
        2 => ['parent' => 1],
        3 => ['parent' => 1],
    ];

    public function parent()
    {
        if ( ! $parentId = static::data($this->id . '.parent')) {
            return null;
        }

        return new static($parentId);
    }

    public function children()
    {
        return $this->traverser()->inferChildren(static::all());
    }
}
