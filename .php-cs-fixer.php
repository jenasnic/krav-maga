<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('var')
    ->notPath('src/Symfony/Component/Translation/Tests/fixtures/resources.php')
    ->in('.')
;

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PHP80Migration' => true,
        '@DoctrineAnnotation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'dir_constant' => true,
        'phpdoc_align' => false,
        'phpdoc_no_package' => false,
        'phpdoc_to_comment' => ['ignored_tags' => ['todo', 'var']],
        'ordered_imports' => false,
        'phpdoc_trim_consecutive_blank_line_separation' => false,
    ])
    ->setFinder($finder)
;
