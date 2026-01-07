CONTAINER ?= php84
DOCKER_RUN=docker compose run --rm $(CONTAINER)

.PHONY: setup tests matrix-tests doc-images

setup:
	docker compose build $(CONTAINER)
	$(DOCKER_RUN) composer update


tests:
	 docker compose run --rm $(CONTAINER) bin/php-cs-fixer fix --verbose --diff --dry-run
	 docker compose run --rm $(CONTAINER) bin/phpunit
	 docker compose run --rm $(CONTAINER) php -d error_reporting='E_ALL & ~E_DEPRECATED' bin/behat --snippets-for

matrix-tests:
	CONTAINER=php81 $(MAKE) setup
	CONTAINER=php81 $(MAKE) tests

	CONTAINER=php82 $(MAKE) setup
	CONTAINER=php82 $(MAKE) tests

	CONTAINER=php83 $(MAKE) setup
	CONTAINER=php83 $(MAKE) tests

doc-images:
	docker compose run --rm php83 rm -f doc/flat.svg doc/flat-square.svg doc/plastic.svg doc/for-the-badge.svg

	docker compose run --rm php83 bin/poser poser FLAT blue -s "flat" -p "doc/flat.svg"
	docker compose run --rm php83 bin/poser poser "FLAT SQUARE" blue -s "flat-square" -p "doc/flat-square.svg"
	docker compose run --rm php83 bin/poser poser PLASTIC blue -s "plastic" -p "doc/plastic.svg"
	docker compose run --rm php83 bin/poser poser "FOR THE BADGE" blue -s "for-the-badge" -p "doc/for-the-badge.svg"
