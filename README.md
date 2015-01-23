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
  * Get callable object and partial application
* `Functools::tuple(mixed $item,...)`
  * Make n-[Tuple](http://en.wikipedia.org/wiki/Tuple)

Usage
-----

### Short syntax

```php
use Teto\Functools as f;
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
