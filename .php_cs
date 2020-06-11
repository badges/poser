<?php
// see https://github.com/FriendsOfPHP/PHP-CS-Fixer

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['vendor'])
;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP71Migration:risky' => true,
        '@PHPUnit60Migration:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => true,
        'declare_strict_types' => false,
        'native_function_invocation' => true,
        'concat_space' => ['spacing' => 'one'],
        'binary_operator_spaces' => ['align_double_arrow' => true, 'align_equals' => true],
        'single_blank_line_at_eof' => true,
        'phpdoc_align' => ['align' => 'vertical'],
        'phpdoc_separation' => true,
        'phpdoc_summary' => false,
        'random_api_migration' => true,
    ])
    ->setFinder($finder)
;
