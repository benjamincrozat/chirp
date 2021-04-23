<?php

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony'               => true,
        'array_syntax'           => ['syntax' => 'short'],
        'binary_operator_spaces' => [
            'align_double_arrow' => true,
            'align_equals'       => true,
        ],
        'cast_spaces'                       => ['space' => 'single'],
        'combine_consecutive_unsets'        => true,
        'compact_nullable_typehint'         => true,
        'concat_space'                      => ['spacing' => 'one'],
        'new_with_braces'                   => false,
        'no_empty_comment'                  => false,
        'no_superfluous_elseif'             => true,
        'not_operator_with_successor_space' => true,
        'ordered_imports'                   => ['sortAlgorithm' => 'length'],
        'return_type_declaration'           => ['space_before' => 'one'],
        'single_trait_insert_per_statement' => false,
        'php_unit_method_casing'            => false,
    ]);
