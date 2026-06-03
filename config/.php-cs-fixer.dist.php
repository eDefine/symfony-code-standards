<?php

$finder = (new PhpCsFixer\Finder())
    ->in(getcwd())
    ->exclude('docker')
    ->exclude('var')
    ->notPath([
        'config/bundles.php',
        'config/preload.php',
        'config/reference.php',
    ])
;

return (new PhpCsFixer\Config())
    ->setCacheFile(getcwd() . '/var/cache/.php-cs-fixer.cache')
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'class_definition' => ['space_before_parenthesis' => true],
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => ['strategy' => 'enforce'],
        'global_namespace_import' => ['import_classes' => true],
        'increment_style' => false,
        'native_constant_invocation' => false,
        'native_function_invocation' => false,
        'no_multiline_whitespace_around_double_arrow' => false, // Breaks multiline arrow functions
        'nullable_type_declaration_for_default_null_value' => true,
        'self_accessor' => false,
        'single_line_throw' => false,
        'yoda_style' => false,
    ])
    ->setFinder($finder)
;
