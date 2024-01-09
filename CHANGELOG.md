## [v3.1.0] - 2024-01-07

### Added
* Implement SvgTextSizeCalculator

### Fixed
* php-cs-fixer run


## [v3.0.0] - 2023-12-11

### Added
* add compatibility to Symfony v7 and PHP 8.3

### Deprecated
* drop compatibility to Symfony v4 and PHP 7.4

### Changed
* update php-cs-fixer deps
* add workflows pipeline for php 8.1, 8.2 and 8.3

## [v2.3.1] - 2022-02-21

### Fixed
* override kartsims/easysvg fork to official package with version 2.4

## [v2.3.0] - 2022-02-18

### Added
* add new style `for-the-badge`

## [v2.2.0] - 2022-01-10

### Added
* add compatibility to Symfony v6

## [v2.1.0] - 2021-07-15

### Removed
* upgrade psalm error level to 2

### Removed
* CircleCI configuration for CI

### Added
* add CircleCI jobs for build and tests with php 8.0
* add Github actions jobs for build and tests with php 7.4 and php 8.0

### Changed
* refactoring of docker-compose to develop with php74 and php80

### Fixed
* removed unnecessary composer symlinks from `bin` dir, removed them from `.gitignore` too

## [v2.0.4] - 2020-10-09

### Changed
* upgrade psalm error level to 3

## [v2.0.3] - 2020-10-08

### Changed
* upgrade psalm error level to 4

## [v2.0.2] - 2020-09-17

### Added
* lightgrey color to color scheme
* grey color to color scheme

## [v2.0.1] - 2020-07-29

### Changed
* fix last argument of generate method to be back-compatible with v1

## [v2.0.0] - 2020-07-29

### Added
* add CircleCI for build and tests
* add changelog
* configure and run php-cs-fixer with new code style roles
* add setting custom badge style from query string
* add psalm
* added all the colors from the shields.io site

### Changed
* upgrade docker-compose for dev environment with php7.4
* refactoring of code to work on php7.4 and drop unsupported deps
* refactoring library to full typed
* upgrade readme
* use class notation in tests
* badge style separated from file format

### Deprecated
* this version works only with php version >= 7.4
* rename SvgRender class to SvgPlasticRender to match the same naming pattern with other render classes
* rename validFormats method to validStyles

### Removed
* travis configuration for the CI

### Fixed
* fixup phpspec data provider
* fix dev autoloader
* remove var_dump from tests


## [v1.4.1] - 2020-06-11

### Fixed
* fixed branch-alias in composer.json


## [v1.4.0] - 2020-06-04

### Changed
* Freeze docker-composer version to php7.1

### Fixed
* fixed security alert for symfony/dependency-injection package


## [v1.3.0] - 2019-01-03

### Added
* allow symfony 4 compatibility

### Changed
* put test dependency under require-dev


## [v1.2.2] - 2016-03-29

### Changed
* extract SVG templates from renderer classes

### Fixed
* small fixes


## [1.2.1] - 2016-03-16

### Changed
* clean code and add scrutinizer integration


## [v1.2.0] - 2016-03-16

### Added
* flat-square format


## [v1.1] - 2015-04-20

### Added
* flat version die to plastic one (added flat format)


## [v1.0] -  2015-01-13
- stable release for poser


[v3.1.0]: https://github.com/badges/poser/tree/v3.1.0
[v3.0.0]: https://github.com/badges/poser/tree/v3.0.0
[v2.3.1]: https://github.com/badges/poser/tree/v2.3.1
[v2.3.0]: https://github.com/badges/poser/tree/v2.3.0
[v2.2.0]: https://github.com/badges/poser/tree/v2.2.0
[v2.1.0]: https://github.com/badges/poser/tree/v2.1.0
[v2.0.4]: https://github.com/badges/poser/tree/v2.0.4
[v2.0.3]: https://github.com/badges/poser/tree/v2.0.3
[v2.0.2]: https://github.com/badges/poser/tree/v2.0.2
[v2.0.1]: https://github.com/badges/poser/tree/v2.0.1
[v2.0.0]: https://github.com/badges/poser/tree/v2.0.0
[v1.4.1]: https://github.com/badges/poser/tree/v1.4.1
[v1.4.0]: https://github.com/badges/poser/tree/v1.4.0
[v1.3.0]: https://github.com/badges/poser/tree/v1.3.0
[v1.2.2]: https://github.com/badges/poser/tree/v1.2.2
[1.2.1]: https://github.com/badges/poser/releases/tag/1.2.1
[v1.2.0]: https://github.com/badges/poser/releases/tag/v1.2.0
[v1.1]: https://github.com/badges/poser/releases/tag/v1.1
[v1.0]: https://github.com/badges/poser/releases/tag/v1.0
