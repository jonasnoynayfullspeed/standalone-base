<?php

$finder = PhpCsFixer\Finder::create()
    ->notPath('bootstrap/cache')
    ->notPath('storage')
    ->notPath('vendor')
    ->notPath('Modules/Chat/vendor')
    ->notPath('Modules/GroupChat/vendor')
    ->notPath('Modules/Home/vendor')
    ->notPath('Modules/Photo/vendor')
    ->notPath('docker')
    ->notPath('public')
    ->notPath('_ide_helper.php')
    ->notPath('_ide_helper_models.php')
    ->notPath('.phpstorm.meta.php')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;

$config = new PhpCsFixer\Config();

return $config->setRules([
        '@Symfony' => true,
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'align',
                '=' => 'align'
            ]
        ],
        'array_syntax' => ['syntax' => 'short'],
        'linebreak_after_opening_tag' => true,
        'not_operator_with_successor_space' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'no_empty_phpdoc' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'single_quote' => true,
        'standardize_not_equals' => true
    ])
    ->setFinder($finder)
;