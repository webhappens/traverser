<?php

namespace WebHappens\Traverser;

use Illuminate\Support\Collection;

class Traverser
{
    public static $defaultRelations = [
        'id' => 'id',
        'parent' => 'parent',
        'children' => 'children',
    ];

    protected $current;
    protected $relations = [];

    public static function make(...$parameters)
    {
        return new static(...$parameters);
    }

    public function __construct($current = null, $relations = [])
    {
        $this->current($current);
        $this->relations($relations);
    }

    public function current($current = null)
    {
        if (is_null($current)) {
            return $this->current;
        }

        $this->current = $current;

        return $this;
    }

    public function relations($relations = null)
    {
        if (is_null($relations)) {
            return $this->relations;
        }

        $this->relations = collect($relations);

        return $this;
    }

    public function id()
    {
        return $this->resolveRelation('id');
    }

    public function parent()
    {
        return $this->resolveRelation('parent');
    }

    public function inferParent($objects)
    {
        return collect($objects)->first(function ($object) {
            return static::make($object, $this->relations())->children()->first(function ($object) {
                return $this->is($object);
            });
        });
    }

    public function children(): Collection
    {
        return $this->resolveRelation('children', collect())
            ->filter()
            ->values();
    }

    public function inferChildren($objects): Collection
    {
        return collect($objects)
            ->filter(function ($object) {
                $parent = static::make($object, $this->relations())->parent();

                return $parent && $this->is($parent);
            })
            ->values();
    }

    public function ancestors(): Collection
    {
        $ancestors = collect();

        if ($parent = $this->parent()) {
            $ancestors->prepend($parent);
            $ancestors = static::make($parent, $this->relations())->ancestors()->merge($ancestors);
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
                ->merge((static::make($child, $this->relations()))->descendants());
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

        return static::make($parent, $this->relations())->children();
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

    protected function resolveRelation($relation, $default = null) {
        $relation = $this->getRelation($relation);

        if ( ! $relation) {
            return $default;
        }

        if (method_exists($this->current(), $relation)) {
            return $this->current()->$relation();
        }

        if (isset($this->current()->$relation)) {
            return $this->current()->$relation;
        }

        return $default;
    }

    protected function getRelation($relation)
    {
        $localRelations = collect($this->relations()->get(get_class($this->current())));

        return $localRelations->get($relation, static::$defaultRelations[$relation]);
    }
}
