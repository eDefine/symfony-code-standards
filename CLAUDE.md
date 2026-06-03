# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this repo is

A Composer library (`edefine/symfony-code-standards`) that packages shared code-quality tooling for Symfony projects. Consuming projects install it via Composer and get a unified `bin/code-check` CLI wrapper around PHPUnit, PHPCS, PHP-CS-Fixer, PHPMD, PHPStan, and Rector, plus pre-configured rulesets for each tool under `config/`.

## Setup

The script requires a `scs.ini` file in the consuming project root. Copy the template and adjust paths:

```shell
cp scs.ini.dist scs.ini
```

All config variables in `scs.ini` point to the tool config files. The `.dist` values assume the library is installed at `vendor/edefine/symfony-code-standards/`.

## Running checks

All checks are run via `bin/code-check` (or `vendor/bin/code-check` in consuming projects):

```shell
bin/code-check                  # run all checks
bin/code-check -U               # PHPUnit only
bin/code-check -C               # PHPCS only
bin/code-check -CF              # PHP-CS-Fixer only
bin/code-check -M               # PHPMD only
bin/code-check -S               # PHPStan only
bin/code-check -R               # Rector only
bin/code-check -f               # attempt auto-fix (applies to PHP-CS-Fixer)
bin/code-check -c               # generate code coverage (requires Xdebug)
bin/code-check -q               # quiet — show only summary table
bin/code-check src/Foo          # restrict to a specific path
```

Individual tools can also be run directly:

```shell
vendor/bin/phpunit --configuration=config/phpunit.dist.xml
vendor/bin/phpcs --standard=config/phpcs.xml.dist src/
vendor/bin/php-cs-fixer fix --config config/.php-cs-fixer.dist.php --dry-run
vendor/bin/phpmd analyze --format=ansi --ruleset=config/phpmd.xml.dist src/ tests/
vendor/bin/phpstan analyse --configuration config/phpstan.neon.dist
vendor/bin/rector process --config=config/rector.php --dry-run
```

## Tool configuration overview

| File | Tool | Key settings |
|---|---|---|
| `config/phpunit.dist.xml` | PHPUnit | Bootstrap at `tests/bootstrap.php`; `requireCoverageMetadata=true`; Symfony clock/DNS mocks for `App` namespace |
| `config/phpcs.xml.dist` | PHPCS | PSR-12 standard; scans `bin/`, `public/`, `src/`, `tests/` |
| `config/.php-cs-fixer.dist.php` | PHP-CS-Fixer | `@Symfony` + `@Symfony:risky` ruleset; `declare_strict_types` enforced; no yoda style; space before parenthesis in class definitions |
| `config/phpmd.xml.dist` | PHPMD | Cyclomatic complexity threshold 21; camel-case naming; no unused code; no `eval`/`exit`/`goto` |
| `config/phpstan.neon.dist` | PHPStan | Level 7; analyses `src/` and `tests/` |
| `config/rector.php` | Rector | PHP 8.3 level set; Symfony/Doctrine/PHPUnit/Twig composer sets; skips `ReadOnlyPropertyRector` (Doctrine UUID bug) |

## Architecture note

Config files use `../../../../` relative paths (e.g. `../../../../src`) because they are stored four directory levels deep inside `vendor/edefine/symfony-code-standards/config/` when installed as a dependency. Rector and PHP-CS-Fixer use `getcwd()` instead, so they resolve relative to the consuming project root.
