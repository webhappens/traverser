<?php

namespace WebHappens\Traverser\Tests\Stubs;

class Page extends Model
{
    public $uri;

    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    protected static $data = [
        'home' => [],
        'about' => ['parent' => 'home'],
        'contact' => ['parent' => 'home'],
    ];

    public function parent()
    {
        if ( ! $parentId = static::data($this->uri . '.parent')) {
            return null;
        }

        return new static($parentId);
    }

    public function children()
    {
        return $this->traverser()->inferChildren(static::all());
    }
}
