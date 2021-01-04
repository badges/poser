# CHANGELOG

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

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


[Unreleased]: https://github.com/badges/poser/compare/v2.0.4...HEAD
[v2.0.4]: https://github.com/badges/poser/tree/v2.0.3
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
