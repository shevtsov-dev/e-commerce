<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__.'/app',
        __DIR__.'/routes',
        __DIR__.'/database',
        __DIR__.'/resources',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->exclude(['vendor', 'storage']);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'align'],
        'blank_line_before_statement' => ['statements' => ['return']],
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'phpdoc_align' => true,
    ])
    ->setFinder($finder);
