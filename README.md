# PHP badges poser [![GitHub actions](https://github.com/badges/poser/workflows/Build/badge.svg)](https://github.com/badges/poser/actions)

This is a php library that creates badges like ![Badge Poser](https://cdn.rawgit.com/badges/poser/master/badge-poser.svg) and ![I'm a badge](https://cdn.rawgit.com/badges/poser/master/i_m-badge.svg) and ![dark](https://cdn.rawgit.com/badges/poser/master/today-dark.svg),
according to [Shields specification](https://github.com/badges/shields#specification).

This library is used by https://poser.pugx.org

[![Latest Stable Version](https://poser.pugx.org/badges/poser/version.svg)](https://packagist.org/packages/badges/poser) [![Latest Unstable Version](https://poser.pugx.org/badges/poser/v/unstable.svg)](//packagist.org/packages/badges/poser) [![Total Downloads](https://poser.pugx.org/badges/poser/downloads.svg)](https://packagist.org/packages/badges/poser)
[![Github actions Build](https://github.com/badges/poser/workflows/Build/badge.svg)](//packagist.org/packages/badges/poser)
[![PHP Version Require](http://poser.pugx.org/badges/poser/require/php)](https://packagist.org/packages/badges/poser)

## Dependencies

* PHP 8.1 or higher
* GD extension

You can still use the library with lower PHP versions by using the old releases

## Use as a command

#### 1. Create a project

```bash
composer create-project badges/poser
ln -s poser/bin/poser /usr/local/bin/poser
```

#### 2. Launch the command

Create an image
```bash
poser license MIT blue -p "license.svg"
```

Flush an image
```bash
poser license MIT blue
```

Choose a different style
```bash
poser license MIT blue -s "for-the-badge"
```

The available styles are `plastic`, `flat`, `flat-square`, `for-the-badge`, and `social`.

You can also use the provided Docker Compose services:

```bash
docker compose run --rm php82 bin/poser license MIT blue
docker compose run --rm php83 bin/poser license MIT blue -p "license.svg"
docker compose run --rm php83 bin/poser license MIT blue -s "plastic" -p "license-plastic.svg"
```


## Usage as library

#### 1. Add to composer dependencies

```bash
composer require badges/poser
```

#### 2. Use in your project as a lib

```php
use PUGX\Poser\Render\SvgPlasticRender;
use PUGX\Poser\Poser;

$render = new SvgPlasticRender();
$poser = new Poser([$render]);

echo $poser->generate('license', 'MIT', '428F7E', 'plastic');
// or
echo $poser->generateFromURI('license-MIT-428F7E.svg?style=plastic');
// or
echo $poser->generateFromURI('license-MIT-428F7E?style=plastic');
// or
$image = $poser->generate('license', 'MIT', '428F7E', 'plastic');

echo $image->getStyle();
```

The allowed styles are: `plastic`, `flat`, `flat-square`, `for-the-badge`, and `social`.

## Badge customization

Badges generated from a URI support these query string options:

| Option | Description |
| --- | --- |
| `style` | Badge style: `plastic`, `flat`, `flat-square`, `for-the-badge`, or `social`. |
| `labelColor` | Color for the left side of the badge. Accepts named colors, 6-digit hex, or 3-digit hex. |
| `logo` | Optional logo as an image URL, `data:image/...` URI, or SVG path data. |
| `logoColor` | Color used when `logo` is SVG path data. Defaults to white. Accepts named colors, 6-digit hex, or 3-digit hex. |

Examples:

```php
echo $poser->generateFromURI('license-MIT-blue.svg?style=social');
echo $poser->generateFromURI('build-passing-brightgreen.svg?labelColor=555');
echo $poser->generateFromURI('github-stars-333.svg?style=social&logo=https%3A%2F%2Fexample.com%2Fgithub.svg');

$logo = rawurlencode('M12 2L2 22h20L12 2z');
echo $poser->generateFromURI('php-8.4-777.svg?logoColor=blueviolet&logo=' . $logo);
```

When these options are used in a URL, encode reserved characters. In particular, encode `#` as `%23`, and encode full image/data URLs passed as `logo`.

The CLI currently exposes style selection with `--style`; `labelColor`, `logo`, and `logoColor` are available through URI-based generation.

## Custom SVG templates

Since `v3.4.0`, SVG templates use `vendorStartX` and `valueStartX` instead of the old `vendorStartPosition` and `valueStartPosition` placeholders. If you maintain custom templates, update them before upgrading:

```diff
- {{ vendorStartPosition }}
- {{ valueStartPosition }}
+ {{ vendorStartX }}
+ {{ valueStartX }}
```

These values are the text center positions multiplied by 10 and are intended to be used with `transform="scale(.1)"`, as the bundled templates do.

The renderer also provides these optional template placeholders: `vendorUpper`, `valueUpper`, `vendorTextLength`, `valueTextLength`, `vendorWidthMinus1`, `valueWidthMinus1`, `valueRectX`, `separatorX`, and `logoElement`.

### Examples (generated with `make doc-images`)

![Flat style](docs/flat.svg)

![Flat square style](docs/flat-square.svg)

![Plastic style](docs/plastic.svg)

![For the badge style](docs/for-the-badge.svg)


## Encoding

Dashes `--` → `-` Dash

Underscores `__` → `_` Underscore

`_` or Space → Space


## More

For *more info* please see the [behat features](./features/)


## Why a composer badge?

Not only because all the other languages already have it, but having the latest stable release in the readme could save time.


## Contributing

Active contribution and patches are very welcome.  
Please refer to [CONTRIBUTING](CONTRIBUTING.md)


## Docker development

You can use the env var CONTAINER to run the tests in a specific PHP version.
The default version if CONTAINER is omitted is php83.

#### 1. Set up your Docker environment

```bash
CONTAINER=php84 make setup
```

#### 2. Run the tests

```bash
CONTAINER=php84 make tests
```

#### 3. Run the tests for all PHP versions

```bash
make matrix-tests
```


## License

[![License](https://poser.pugx.org/badges/poser/license.svg)](./LICENSE)
