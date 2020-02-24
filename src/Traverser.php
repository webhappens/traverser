<?php

namespace WebHappens\Traverser;

use Illuminate\Support\Collection;

class Traverser
{
    public static $defaultMaps = [
        'id' => 'id',
        'parent' => 'parent',
        'children' => 'children',
    ];

    protected $current;
    protected $maps = [];

    public static function make(...$parameters)
    {
        return new static(...$parameters);
    }

    public function __construct($current = null, $maps = [])
    {
        $this->current($current);
        $this->maps($maps);
    }

    public function current($current = null)
    {
        if (is_null($current)) {
            return $this->current;
        }

        $this->current = $current;

        return $this;
    }

    public function maps($maps = null)
    {
        if (is_null($maps)) {
            return $this->maps;
        }

        $this->maps = collect($maps);

        return $this;
    }

    public function id()
    {
        return $this->resolveMapping('id');
    }

    public function parent()
    {
        return $this->resolveMapping('parent');
    }

    public function inferParent($objects)
    {
        return collect($objects)->first(function ($object) {
            return static::make($object, $this->maps())->children()->first(function ($object) {
                return $this->is($object);
            });
        });
    }

    public function children(): Collection
    {
        return $this->resolveMapping('children', collect())
            ->filter()
            ->values();
    }

    public function inferChildren($objects): Collection
    {
        return collect($objects)
            ->filter(function ($object) {
                $parent = static::make($object, $this->maps())->parent();

                return $parent && $this->is($parent);
            })
            ->values();
    }

    public function ancestors(): Collection
    {
        $ancestors = collect();

        if ($parent = $this->parent()) {
            $ancestors->prepend($parent);
            $ancestors = static::make($parent, $this->maps())->ancestors()->merge($ancestors);
        }

        return $ancestors;
    }

    public function ancestorsAndSelf(): Collection
    {
        return $this->ancestors()->push($this->current());
    }

    public function descendants(): Collection
    {
        $descendants = collect();

        $this->children()->each(function($child) use (&$descendants) {
            $descendants = $descendants
                ->push($child)
                ->merge((static::make($child, $this->maps()))->descendants());
        });

        return $descendants;
    }

    public function descendantsAndSelf(): Collection
    {
        return $this->descendants()->prepend($this->current());
    }

    public function siblings(): Collection
    {
        return $this->siblingsAndSelf()->reject(function ($object) {
            return $this->is($object);
        });
    }

    public function siblingsAndSelf(): Collection
    {
        if ( ! $parent = $this->parent()) {
            return collect([$this->current()]);
        }

        return static::make($parent, $this->maps())->children();
    }

    public function siblingsNext()
    {
        return $this->siblingsAfter()->first();
    }

    public function siblingsAfter(): Collection
    {
        return $this->siblingsAndSelf()
            ->slice($this->siblingsPosition()+1)
            ->values();
    }

    public function siblingsPrevious()
    {
        return $this->siblingsBefore()->last();
    }

    public function siblingsBefore(): Collection
    {
        return $this->siblingsAndSelf()
            ->slice(0, $this->siblingsPosition())
            ->values();
    }

    public function siblingsPosition()
    {
        return $this->siblingsAndSelf()->search(function ($sibling) {
            return $this->is($sibling);
        });
    }

    protected function is($object): bool
    {
        return $object == $this->current();
    }

    protected function resolveMapping($for, $default = null) {
        $name = collect(
            $this->maps()->get(get_class($this->current()))
        )->get($for, static::$defaultMaps[$for]);

        if ( ! $name) {
            return $default;
        }

        if (method_exists($this->current(), $name)) {
            return $this->current()->$name();
        }

        if (isset($this->current()->$name)) {
            return $this->current()->$name;
        }

        return $default;
    }
}
