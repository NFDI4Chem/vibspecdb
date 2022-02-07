
<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['bootstrap', 'storage', 'vendor'])
    ->name('*.php')
    ->name('_ide_helper')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'no_unused_imports' => true,
    'align_multiline_comment' => [
      // 'comment_type' => 'all_multiline',
    ],
    'cast_spaces' => ['space' => 'single'],
    // 'concat_space' => ['spacing' => 'one'],
    'no_useless_return' => true,
    'not_operator_with_space' => false,
    'ordered_class_elements' => true,
    'ordered_imports' => true,
    'yoda_style' => [
        'equal' => false,
        'identical' => false,
        'less_and_greater' => false
    ],
    'full_opening_tag' => false,
    'php_unit_construct' => true,
    'php_unit_strict' => true,
    'phpdoc_order' => true,
])
    ->setFinder($finder);