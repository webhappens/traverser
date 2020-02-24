<?php

namespace WebHappens\Traverser\Tests\Stubs;

class Category extends Model
{
    protected static $data = [
        1 => [],
        2 => ['categories' => [3], 'posts' => [1, 2, 3]],
        3 => ['posts' => [4, 5]],
    ];

    public function parent()
    {
        return $this->traverser()->inferParent(static::all());
    }

    public function children()
    {
        return array_merge($this->categories(), $this->posts());
    }

    public function categories()
    {
        return collect(static::data($this->id . '.categories'))
            ->mapInto(Category::class)
            ->toArray();
    }

    public function posts()
    {
        return collect(static::data($this->id . '.posts'))
            ->mapInto(Post::class)
            ->toArray();
    }
}
