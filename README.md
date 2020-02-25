![tests](https://github.com/webhappens/traverser/workflows/tests/badge.svg)

# Traverser

Easily traverse nested object structures without worrying about recursion. Great for building menus, navigation, sitemaps and more!

 - [Installation](#installation)
 - [Getting started](#getting-started)
 - [Custom mapping names](#custom-mapping-names)
 - [Inferring parent and children](#inferring-parent-and-children)
 - [Traversal methods](#traversal-methods)

## Installation

Install via composer:

```shell
composer require webhappens/traverser
```

Import the class into your namespace:

```php
use WebHappens\Traverser\Traverser;
```

## Getting started

In order for the traverser to understand your hierarchy, you must ensure it can resolve "id", "parent" and "children" for each class in that hierarchy.

By default, it will look for "methods" or "properties" (in that order) by each of these names.

Your classes should typically look something like this:

```php
public $id;

/**
 * @return object|null
 */
public function parent()
{
    //
}

/**
 * @return array
 */
public function children()
{
    //
}
```

Next, create a new instance of the traverser class and pass in an instance of the class you'd like to use as your starting point for traversal.

```php
$traverser = Traverser::make($current);
```

You can now call the various traversal methods on this instance.

```php
$descendants = $traverser->descendants();
```

As a convenience you might want to put this on a base class or trait:

```php
// Within a base class or trait

public function traverser()
{
    return Traverser::make($this);
}
```

And then call it like this:

```php
// Within a class that extends the base class / uses the trait

$this->traverser()->descendants();
```

For a deeper insight on using the traverser you should take a look at the tests.

## Custom mapping names

You may override the default "id", "parent" and "children" mapping names for each class if you want to.

To do this pass a second argument into the constructor containing a custom mapping array.

```php
$traverser = Traverser:make($current, [
    Page::class => ['id' => 'uri'],
    Post::class => ['parent' => 'category', 'children' => 'comments'],
    Comment::class => ['parent' => 'post'],
]);
```

If you're using Laravel you may want to bind this to the Service Container.

```php
// Within AppServiceProvider.php

$this->app->bind('traverser', function () {
    return \WebHappens\Traverser\Traverser::make()->maps([...]);
});
```

And then resolve it like this:

```php
// Within your application code

$traverser = resolve('traverser')->current($current);
```

Note that you can construct a new traverser instance without passing any arguments and use the `maps` and `current` methods instead.

## Inferring parent and children

In most situations your objects will only have data about who their "parent" is or who their "children" are, not both. For this reason the traverser allows you to easily infer one from the other.

To infer parent you should pass an array of all possible parents into the `inferParent` method. Each of these objects must be able to resolve its own children.

```php
// Within Category.php

public function parent()
{
    return $this->traverser()->inferParent(static::all());
}

public function children()
{
    return array_merge($this->categories(), $this->posts());
}

// Within Post.php

public function category()
{
    return $this->traverser()->inferParent(Category::all());
}
```

To infer children you should pass an array of all possible children into the `inferChildren` method. Each of these objects must be able to resolve its own parent.

```php
// Within Page.php

public function parent()
{
    // ...
    return new static($parentId);
}

public function children()
{
    return $this->traverser()->inferChildren(static::all());
}
```

## Traversal methods

```php
$parent = $this->traverser()->parent();
$children = $this->traverser()->children();
$ancestors = $this->traverser()->ancestors();
$ancestorsAndSelf = $this->traverser()->ancestorsAndSelf();
$descendants = $this->traverser()->descendants();
$descendantsAndSelf = $this->traverser()->descendantsAndSelf();
$siblings = $this->traverser()->siblings();
$siblingsAndSelf = $this->traverser()->siblingsAndSelf();
$siblingsNext = $this->traverser()->siblingsNext();
$siblingsAfter = $this->traverser()->siblingsAfter();
$siblingsPrevious = $this->traverser()->siblingsPrevious();
$siblingsBefore = $this->traverser()->siblingsBefore();
$siblingsPosition = $this->traverser()->siblingsPosition();
```

## Credits

 - Ben Gurney: ben@webhappens.co.uk
 - Sam Leicester: sam@webhappens.co.uk

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
