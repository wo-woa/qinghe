<?php

$header = <<<EOF
This file is part of the EasyWeChatComposer.

(c) mingyoung <mingyoungcheung@gmail.com>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'header_comment' => ['header' => $header],
        'declare_strict_types' => true,
        'ordered_imports' => true,
        'strict_comparison' => true,
        'no_empty_comment' => false,
        'yoda_style' => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->in(__DIR__)
    )
;
