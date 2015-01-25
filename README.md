Teto Functools
==============

[![Package version](http://img.shields.io/packagist/v/zonuexe/functools.svg?style=flat)](https://packagist.org/packages/zonuexe/functools)
[![Build Status](https://travis-ci.org/zonuexe/php-functools.svg?branch=master)](https://travis-ci.org/zonuexe/php-functools)
[![Packagist](http://img.shields.io/packagist/dt/zonuexe/php-functools.svg?style=flat)](https://packagist.org/packages/zonuexe/functools)

Functional toolbox for PHP

Installation
------------

### Composer

```
composer require zonuexe/functools
```

Features
--------

* `Functools::partial(callable $callback, array $arguments, int $pos)`
  * Partial application for [callable](http://php.net/manual/language.types.callable.php) (`arary_map` friendly)
* `Functools::arity(callable $callback)`
  * Analyze [arity](http://en.wikipedia.org/wiki/Arity)(number of arguments) of `$callback`.
* `Functools::curry(callable $callback)`
  * [Currying](http://en.wikipedia.org/wiki/Currying) `$callback` object.
* `Functools::op(string $symbol, [array $arguments, int $pos])`
  * Get callable object (and partial application.)
* `Functools::collback(callable $callback,...)`
  * [Function composition](http://en.wikipedia.org/wiki/Function_composition_%28computer_science%29).
* `Functools::tuple(mixed $item,...)`
  * Make n-[Tuple](http://en.wikipedia.org/wiki/Tuple).
* `Functools::fix(callable $callback)`
  * [Anonymous recursion](http://en.wikipedia.org/wiki/Anonymous_recursion) ([fixed-point combinator](http://en.wikipedia.org/wiki/Fixed-point_combinator))

Iteration
---------

This library does not have iterator. You will be able to combine it by selecting a favorite of iterator library.

* PHP [`array`](http://php.net/manual/language.types.array.php) and functions
  * [PHP: array_map - Manual](http://php.net/manual/function.array-map.php)
  * [PHP: array_reduce - Manual](http://php.net/manual/function.array-reduce.php)
  * [PHP: array_filter - Manual](http://php.net/manual/function.array-filter.php)
* [Underbar.php - A collection processing library for PHP, like Underscore.js.](http://emonkak.github.io/underbar.php/)
  * [emonkak/underbar.php](https://github.com/emonkak/underbar.php)
* Ginq
  * [akanehara/ginq](https://github.com/akanehara/ginq)
* nikic\iter
  * [nikic/iter](https://github.com/nikic/iter)

Usage
-----

Japanese version: http://qiita.com/tadsan/items/abc52f0739ab5b4e781b

### Short syntax

```php
use Teto\Functools as f;
```

### f::partial()

```php
$comma = f::partial("implode", [", "]);
$comma(range(1, 10));
// "1, 2, 3, 4, 5, 6, 7, 8, 9, 10"

$join_10 = f::partial("implode", [1 => range(1, 10)], 0);
$join_10("@");
// "1@2@3@4@5@6@7@8@9@10"
$join_10("\\");
=> "1\\2\\3\\4\\5\\6\\7\\8\\9\\10"

$sleep3 = f::partial("sleep", [3]);
$sleep3();
$sleep3("foo"); // Error!

$sleep3 = f::partial("sleep", [3], -1);
$sleep3("foo"); // OK!
```

### f::op()

```php
$add = f::op("+");
$add(2, 3); // (2 + 3) === 5

$add_1 = f::op("+", [1]);
$add_1(4);  // (1 + 4) === 5

$half = f::op("/", [1 => 2], 0);
$half(10);  // (10 / 2) === 5
```

### f::tuple()

```php
$teto  = f::tuple("Teto Kasane",  31, "2008-04-01", "Baguette");
$ritsu = f::tuple("Ritsu Namine", 6,  "2009-10-02", "Napa cabbage");

// index access
$teto[0]; // "Teto Kasane"
$teto[1]; // 31
$teto[2]; // "2008-04-01"
$teto[3]; // "Baguette"

// property access

$tetop  = f::tuple("name", "Teto Kasane",  "age", 31, "birthday", "2008-04-01", "item", "Baguette");
$ritsup = f::tuple("name", "Ritsu Namine", "age",  6, "birthday", "2009-10-02", "item", "Napa cabbage");
$tetop->pget("name");     // "Teto Kasane"
$tetop->pget("age");      // 31
$tetop->pget("birthday"); // "2008-04-01"
$tetop->pget("item");     // "Baguette"
```

### f::fix()

```php
$fib = f::fix(function ($fib) {
    return function ($x) use ($fib) {
        return ($x < 2) ? 1 : $fib($x - 1) + $fib($x -2);
    };
});

$fib(6); // 13
```

Copyright
---------

see `./LICENSE`.

    Functional toolbox
    Copyright (c) 2015 USAMI Kenta <tadsan@zonu.me>

Teto Kasane
-----------

I love [Teto Kasane](http://utau.wikia.com/wiki/Teto_Kasane). (ja: [Teto Kasane official site](http://kasaneteto.jp/))

```
　　　　　 　r /
　 ＿＿ , --ヽ!-- .､＿
　! 　｀/::::;::::ヽ l
　!二二!::／}::::丿ハﾆ|
　!ﾆニ.|:／　ﾉ／ }::::}ｺ
　L二lイ　　0´　0 ,':ﾉｺ
　lヽﾉ/ﾍ､ ''　▽_ノイ ソ
 　ソ´ ／}｀ｽ /￣￣￣￣/
　　　.(_:;つ/  0401 /　ｶﾀｶﾀ
 ￣￣￣￣￣＼/＿＿＿＿/
```
