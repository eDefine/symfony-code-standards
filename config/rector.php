<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Set\ValueObject\LevelSetList;

return RectorConfig::configure()
    ->withAttributesSets(all: true)
    ->withComposerBased(twig: true, doctrine: true, phpunit: true, symfony: true)
    ->withPaths([
        getcwd() . '/src',
        getcwd() . '/tests',
    ])
    ->withPHPStanConfigs([getcwd() . '/vendor/edefine/symfony-code-standards/config/phpstan.neon.dist'])
    ->withSets([LevelSetList::UP_TO_PHP_83])
    ->withSkip([
        ReadOnlyPropertyRector::class, // "private readonly Uuid $id" is not working, doctrine bug
    ])
;
