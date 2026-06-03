# eDefine Symfony Code Standards

A Composer library that packages shared code-quality tooling for Symfony projects. It provides a unified CLI wrapper (`bin/code-check`) around PHPUnit, PHPCS, PHP-CS-Fixer, PHPMD, PHPStan, and Rector, along with pre-configured rulesets for each tool.

## Installation

```shell
composer require --dev edefine/symfony-code-standards
```

## Setup

The `bin/code-check` script requires an `scs.ini` file in your project root. Copy the template distributed with this package and adjust paths as needed:

```shell
cp vendor/edefine/symfony-code-standards/scs.ini.dist scs.ini
```

`scs.ini.dist` defaults:

```ini
DOMAIN=http://localhost
PHPUNIT_CONFIG=vendor/edefine/symfony-code-standards/config/phpunit.dist.xml
PHPCS_CONFIG=vendor/edefine/symfony-code-standards/config/phpcs.xml.dist
PHP_CS_FIXER_CONFIG=vendor/edefine/symfony-code-standards/config/.php-cs-fixer.dist.php
PHPMD_CONFIG=vendor/edefine/symfony-code-standards/config/phpmd.xml.dist
PHPSTAN_CONFIG=vendor/edefine/symfony-code-standards/config/phpstan.neon.dist
RECTOR_CONFIG=vendor/edefine/symfony-code-standards/config/rector.php
```

Each variable must point to a readable file. You can override any of them to use a project-specific config.

## Usage

```shell
vendor/bin/code-check [options] [path]
```

Running without options executes all checks.

### Analysis options

| Flag | Tool |
| --- | --- |
| `-U` / `--phpunit` | PHPUnit |
| `-C` / `--phpcs` | PHPCS |
| `-CF` / `--php-cs-fixer` | PHP-CS-Fixer |
| `-M` / `--phpmd` | PHPMD |
| `-S` / `--phpstan` | PHPStan |
| `-R` / `--rector` | Rector |

### Miscellaneous options

| Flag | Description |
| --- | --- |
| `-c` / `--coverage` | Generate code coverage reports (requires Xdebug) |
| `-f` / `--fix` | Auto-fix issues where possible (PHP-CS-Fixer) |
| `-q` / `--quiet` | Suppress per-tool output; show only the summary table |
| `-v` / `--verbose` | Show additional per-tool output |
| `-u` / `--usage` | Print usage information |

An optional `path` argument restricts analysis to a specific file or directory.

### Examples

```shell
# Run all checks
vendor/bin/code-check

# Run only PHPStan and Rector
vendor/bin/code-check -S -R

# Fix code style issues
vendor/bin/code-check -CF -f

# Run PHPUnit with coverage on a specific path
vendor/bin/code-check -U -c src/Module/

# Quiet mode — useful in CI where you only need the summary
vendor/bin/code-check -q
```

## Tool configuration

All configs live in `config/` and are ready to use as-is or as a base for project overrides.

### PHPUnit (`config/phpunit.dist.xml`)

- Bootstrap: `tests/bootstrap.php`
- `requireCoverageMetadata="true"` — all source files must declare coverage metadata
- `failOnNotice` and `failOnWarning` enabled
- Symfony PHPUnit bridge extension with clock and DNS mocks scoped to the `App` namespace
- Coverage output written to `public/coverage-phpunit/`

### PHPCS (`config/phpcs.xml.dist`)

- Standard: **PSR-12**
- Scans `bin/`, `public/`, `src/`, `tests/`
- Excludes `config/reference.php`

### PHP-CS-Fixer (`config/.php-cs-fixer.dist.php`)

- Ruleset: `@Symfony` + `@Symfony:risky`
- `declare_strict_types` enforced on every file
- Global namespace imports enabled (`use` statements over FQCNs in code)
- Concatenation spacing: one space around `.`
- Space before parenthesis in class definitions
- Yoda style disabled
- Single-line throw disabled
- Excludes `docker/`, `var/`, and `config/bundles.php`, `config/preload.php`, `config/reference.php`

### PHPMD (`config/phpmd.xml.dist`)

Active rules:

- **Codesize** – cyclomatic complexity (threshold: 21)
- **Cleancode** – duplicated array keys, error control operator (`@`), missing imports, undefined variables
- **Controversial** – camel-case naming for classes, methods, parameters, properties, and variables; no superglobals
- **Design** – no `eval`, `exit`, `goto`, `count()` in loops, empty catch blocks, or development fragments (`var_dump` etc.)
- **Naming** – constant naming conventions
- **Unusedcode** – unused local variables, private fields, and private methods

### PHPStan (`config/phpstan.neon.dist`)

- Level: **7**
- Analyses `src/` and `tests/`
- `treatPhpDocTypesAsCertain: false`

### Rector (`config/rector.php`)

- PHP level set: **UP_TO_PHP_83**
- Composer-based sets enabled: Symfony, Doctrine, PHPUnit, Twig
- Attribute sets: all enabled
- PHPStan config shared from this package
- Processes `src/` and `tests/`
- `ReadOnlyPropertyRector` skipped — Doctrine has a bug with `private readonly Uuid $id`
