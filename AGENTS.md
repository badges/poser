# AGENTS.md

Guidance for coding agents working on this repository.

## Project Overview

This is `badges/poser`, a PHP library and CLI tool that generates Shields-style SVG badges. It is used by `https://poser.pugx.org`.

Core areas:

- `src/Badge.php`: parses badge input/URIs, normalizes colors, escaping, style, format, label color, and logo options.
- `src/Poser.php`: main facade that maps badge styles to renderers.
- `src/Render/`: SVG renderers and shared local-template rendering logic.
- `src/Resources/templates/`: SVG templates for each style.
- `src/Calculator/`: text width calculators used to size badges.
- `src/UI/` and `bin/poser`: Symfony Console CLI entrypoint.
- `tests/`: PHPUnit tests and SVG fixtures.
- `features/`: Behat CLI scenarios and fixtures.
- `doc/`: generated example badge images.

The package is PSR-4 autoloaded under `PUGX\Poser\`.

## Requirements

- PHP `^8.1`
- Composer
- PHP extensions: `gd`, `simplexml`
- Docker Compose is the preferred reproducible development path.

The current Makefile defaults to `CONTAINER=php84`. The README still mentions `php83` in a few places, so treat `Makefile`, `composer.json`, and CI workflows as the more current sources of truth.

## Common Commands

Install/update dependencies in Docker:

```bash
CONTAINER=php84 make setup
```

Run the full local Makefile test target:

```bash
CONTAINER=php84 make tests
```

This runs:

- PHP-CS-Fixer dry run
- PHPUnit
- Behat

Useful focused commands:

```bash
bin/phpunit
bin/phpunit tests/Render/SvgFlatRenderTest.php
bin/php-cs-fixer fix --verbose --diff --dry-run
bin/php-cs-fixer fix
php -d error_reporting='E_ALL & ~E_DEPRECATED' bin/behat --snippets-for
bin/psalm
composer validate
```

Docker equivalents:

```bash
docker compose run --rm php84 bin/phpunit
docker compose run --rm php84 bin/php-cs-fixer fix --verbose --diff --dry-run
docker compose run --rm php84 php -d error_reporting='E_ALL & ~E_DEPRECATED' bin/behat --snippets-for
```

Generate documentation badge SVGs:

```bash
make doc-images
```

## CI Expectations

GitHub Actions runs:

- PHP-CS-Fixer on PHP 8.1
- PHPUnit on PHP 8.1, 8.2, 8.3, and 8.4
- `composer validate`
- Commit linting on pull requests

Before a PR, run the most relevant local tests plus CS checks. For broad changes, prefer `CONTAINER=php84 make tests`; for compatibility-sensitive changes, run the matrix target or targeted Docker services.

## Coding Style

- Follow the existing PHP style and namespace layout.
- PHP-CS-Fixer config is in `.php-cs-fixer.php`.
- The rule set is Symfony-based with PHP 8.1 migration rules and risky rules enabled.
- Source files generally do not declare strict types, while newer tests do. Match the surrounding file style.
- Prefer constructor injection already used by renderers, especially for text calculators and template paths.
- Keep renderer classes small; shared behavior belongs in `LocalSvgRenderer` or `SvgBaseRenderer` when it applies to multiple styles.

## Testing Notes

- PHPUnit fixtures compare generated SVG output, often exactly after template rendering. Small whitespace or sizing changes can be behaviorally significant.
- Renderer tests commonly mock `TextSizeCalculatorInterface` to stabilize dimensions.
- Behat scenarios exercise CLI behavior and compare output similarity.
- If you change URI parsing, escaping, colors, logos, label colors, sizing, or template placeholders, update/add focused PHPUnit coverage and consider the Behat CLI path.
- If you change SVG templates, update affected fixtures under `tests/Fixtures/` and generated docs only when intentional.

## SVG Rendering Notes

- `LocalSvgRenderer::render()` loads a style template, builds replacement parameters, replaces `{{ name }}` placeholders, normalizes whitespace, validates XML, and returns an `Image`.
- Templates live in `src/Resources/templates/{style}.svg`.
- Supported styles currently include `flat`, `flat-square`, `plastic`, `for-the-badge`, and `social` in code/tests/templates, even though README examples list fewer styles.
- Badge color names are mapped in `Badge::$colorScheme`; arbitrary 3- or 6-digit hex values are also accepted.
- Logo handling supports SVG path data, `data:image/...`, base64 SVG data URLs, and HTTP URLs.

## Dependency Hygiene

- Do not edit `vendor/`.
- Do not commit `.php-cs-fixer.cache`, `.php_cs.cache`, coverage output, or IDE files.
- Composer dependencies are sorted by config; use Composer commands rather than hand-editing lockfile content.

## Git and Contribution Notes

- Commit messages must always follow Conventional Commits.
- Branch names created for repository work must follow the same Conventional Commits intent, using a lowercase type prefix and short kebab-case description, for example `docs/documentation-alignment`, `fix/logo-query-encoding`, or `feat/social-badge-docs`.
- Preserve existing user changes in the worktree. Check `git status --short` before editing and avoid unrelated rewrites.
- Keep changes narrowly scoped. This library relies on stable SVG output, so avoid opportunistic refactors around rendering unless they are needed for the task.

## Quick Orientation Checklist

When starting future work:

1. Run `git status --short`.
2. Read the relevant source and matching tests/fixtures before editing.
3. For renderer changes, inspect both `src/Resources/templates/` and `tests/Fixtures/`.
4. Run the narrowest useful PHPUnit test first.
5. Run CS fixer dry run and broader tests when the change touches shared rendering, parsing, CLI behavior, or dependencies.
