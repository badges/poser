# PHP badges poser [![Gitbub actions](https://github.com/badges/poser/workflows/Build/badge.svg)](https://github.com/badges/poser/actions)

This is a php library that creates badges like ![Badge Poser](https://cdn.rawgit.com/badges/poser/master/badge-poser.svg) and ![I'm a badge](https://cdn.rawgit.com/badges/poser/master/i_m-badge.svg) and ![dark](https://cdn.rawgit.com/badges/poser/master/today-dark.svg),
according to [Shields specification](https://github.com/badges/shields#specification).

This library is used by https://poser.pugx.org

[![Latest Stable Version](https://poser.pugx.org/badges/poser/version.svg)](https://packagist.org/packages/badges/poser) [![Latest Unstable Version](https://poser.pugx.org/badges/poser/v/unstable.svg)](//packagist.org/packages/badges/poser) [![Total Downloads](https://poser.pugx.org/badges/poser/downloads.svg)](https://packagist.org/packages/badges/poser)
[![Github actions Build](https://github.com/badges/poser/workflows/Build/badge.svg)](//packagist.org/packages/badges/poser)

## Dependencies

* PHP 7.4 or higher
* GD extension

to use the library with lower php version use the tag [v1.4](https://github.com/badges/poser/tree/v1.4.0)

## Use as `phar` command

### 1. Download the phar
```bash
wget -q -O /usr/local/bin/poser https://github.com/.../poser.phar 
chmod +x /usr/local/bin/poser
```

### 2. Launch the command

Create an image
```bash
poser license MIT blue -p "license.svg"
```

Flush an image
```bash
poser license MIT blue
```


## Use as `bin` command

### 1. Create a project

```bash
composer create-project badges/poser
ln -s poser/bin/poser /usr/local/bin/poser
```

### 2. Launch the command

Create an image
```bash
poser license MIT blue -p "license.svg"
```

Flush an image
```bash
poser license MIT blue
```

## Usage as library

### 1. Add to composer dependencies

```bash
composer require badges/poser
```

### 2. Use in your project as lib

```php
use PUGX\Poser\Render\SvgPlasticRender;
use PUGX\Poser\Poser;

$render = new SvgPlasticRender();
$poser = new Poser($render);

echo $poser->generate('license', 'MIT', '428F7E', 'plastic');
// or
echo $poser->generateFromURI('license-MIT-428F7E.svg?style=plastic');
// or
echo $poser->generateFromURI('license-MIT-428F7E?style=plastic');
// or
$image = $poser->generate('license', 'MIT', '428F7E', 'plastic');

echo $image->getStyle();
```

The allowed styles are: `plastic`, `flat` and `flat-square`.


## Encoding

Dashes `--` → `-` Dash

Underscores `__` → `_` Underscore

`_` or Space → Space


## More

For *more info* please see the [behat features](./features/)
and the examples in the [php-spec folder](./spec/)


## Why a composer badge?

Not only because all the other languages already have it, but having the latest stable release in the readme could save time.


## Contributing

Active contribution and patches are very welcome.  
Please refer to [CONTRIBUTING](CONTRIBUTING.md)


## License

[![License](https://poser.pugx.org/badges/poser/license.svg)](./LICENSE)
