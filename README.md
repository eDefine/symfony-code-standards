# eDefine Symfony Code Standards

## bin/code-check

Runs multiple code checks such as:
* PHPUnit (with code coverage)
* PHPCS
* PHP-CS-Fixer
* PHPMD
* PHPStan
* Rector

For usage see:
```shell
$ bin/code-check -u

Usage:
  bin/code-check [options] [path]

Analysis Options:
  -U|--phpunit       Run PHPUnit tests
  -C|--phpcs         Run PHPCS checks
  -CF|--php-cs-fixer Run PHP-CS-Fixer checks
  -M|--phpmd         Run PHPMD checks
  -S|--phpstan       Run PHPStan checks
  -R|--rector        Run Rector checks

Miscellaneous Options:
  -c|--coverage      Generate code coverage reports
  -f|--fix           Try to fix problems
  -q|--quiet         Output less information
  -v|--verbose       Output more information
  -u|--usage         Output this message
```
