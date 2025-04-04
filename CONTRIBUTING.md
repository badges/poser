# Contributing

Active contribution and patches are very welcome.
See the [github issues](https://github.com/badges/poser/issues?state=open).
To keep things in shape we have quite a bunch of examples and features. If you're submitting pull requests please
make sure that they are still passing and if you add functionality please
take a look at the coverage as well it should be pretty high :)

- First fork or clone the repository

```bash
git clone git://github.com/badges/poser.git
cd poser
```

- Install vendors:

```bash
composer install
```

- Run specs:

```bash
composer phpspec
```

- Then run behat:

```bash
composer behat
```


## Using Docker

```bash
docker-compose up --build -d
docker-compose exec php83 composer install
docker-compose exec php83 composer phpspec
docker-compose exec php83 composer behat
docker-compose exec php83 composer php-cs-fixer
```

or


## Pull Request

Please before to push a PR execute php-cs-fixer for the code check-style and run all tests

```bash
$ composer php-cs-fixer
$ composer phpspec
$ composer behat
```

## Commit guidelines

Please see the [Conventional Commits](https://conventionalcommits.org) for commit guidelines.


## How build new images on M1
```shell
$ docker run --privileged --rm tonistiigi/binfmt --install all
$ docker buildx create --name mybuilder
$ docker buildx use mybuilder
$ docker buildx build \
  --platform linux/amd64,linux/arm64 \
  --build-arg BUILDKIT_INLINE_CACHE=1 \
  --push \
  -t pugx/poser:php81 \
  -f .docker/base/php81/Dockerfile \
  .
```

## Build a new PHP image on Linux

* create a new PHP conf, e.g. `.docker/base/php84/Dockerfile` and `.docker/development/php84/Dockerfile `
* `docker build -t poser-php84 --pull=false .docker/base/php84`
* `docker login`
* `docker tag poser-php84 pugx/poser:php84`
* `docker push pugx/poser:php84`


## ENJOY
