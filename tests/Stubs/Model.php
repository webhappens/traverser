<?php

namespace WebHappens\Traverser\Tests\Stubs;

use Illuminate\Support\Arr;
use WebHappens\Traverser\Traverser;

class Model
{
    public $id;
    protected static $data = [];

    public function __construct($id)
    {
        $this->id = $id;
    }

    public static function all()
    {
        return collect(static::data())->keys()->mapInto(static::class);
    }

    public function traverser()
    {
        return Traverser::make($this, [
            Page::class => ['id' => 'uri'],
            Post::class => ['parent' => 'category', 'children' => 'comments'],
            Comment::class => ['parent' => 'post'],
        ]);
    }

    protected static function data($key = null)
    {
        $data = static::$data;

        return $key ? Arr::get($data, $key) : $data;
    }
}
