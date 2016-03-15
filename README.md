PHP badges poser
================

This is a php library that creates badges like ![Badge Poser](https://cdn.rawgit.com/badges/poser/master/badge-poser.svg) and ![I'm a badge](https://cdn.rawgit.com/badges/poser/master/i_m-badge.svg) and ![dark](https://cdn.rawgit.com/badges/poser/master/today-dark.svg),
according to [Shields specification](https://github.com/badges/shields#specification).

This library is used by https://poser.pugx.org

[![Latest Stable Version](https://poser.pugx.org/badges/poser/version.svg)](https://packagist.org/packages/badges/poser) [![Latest Unstable Version](https://poser.pugx.org/badges/poser/v/unstable.svg)](//packagist.org/packages/badges/poser) [![Total Downloads](https://poser.pugx.org/badges/poser/downloads.svg)](https://packagist.org/packages/badges/poser)
[![Build Status](https://travis-ci.org/badges/poser.svg?branch=master)](https://travis-ci.org/badges/poser)

## Use as command

#### 1. Create a project

``` bash
$ composer create-project badges/poser ~0.1
$ ln -s poser/bin/poser /usr/local/bin/poser
```

#### 2. Launch the command

Create an image

`$ poser license MIT blue -p "license.svg"`

Flush an image

`$ poser license MIT blue`

## Usage as library

#### 1. Add to composer

`composer require badges/poser ~0.1`

#### 2. Use in your project as lib

``` php
    use PUGX\Poser\Render\SvgRender;
    use PUGX\Poser\Poser;

    $render = new SvgRender();
    $poser = new Poser(array($render));

    echo $poser->generate('license', 'MIT', '428F7E', 'plastic');
    // or
    echo $poser->generateFromURI('license-MIT-428F7E.plastic');
    // or
    $image = $poser->generate('license', 'MIT', '428F7E', 'plastic');

    echo $image->getFormat();
```

## Encoding

Dashes `--` → `-` Dash

Underscores `__` → `_` Underscore

`_` or Space → Space

## More

For *more info* please see the [behat features](./features/)
and the examples in the [php-spec folder](./spec/)

## Contribution

Active contribution and patches are very welcome.
See the [github issues](https://github.com/PUGX/poser/issues?state=open).
To keep things in shape we have quite a bunch of examples and features. If you're submitting pull requests please
make sure that they are still passing and if you add functionality please
take a look at the coverage as well it should be pretty high :)

- First fork or clone the repository

```
git clone git://github.com/badges/poser.git
cd poser
```

- Install vendors:

``` bash
composer install
```

- Run specs:

``` bash
./bin/phpspec run --format=pretty
```

- Then run behat:

``` bash
./bin/behat run
```

## License

[![License](https://poser.pugx.org/badges/poser/license.svg)](./LICENSE)

