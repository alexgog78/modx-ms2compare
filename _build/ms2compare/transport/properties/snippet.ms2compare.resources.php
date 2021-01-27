<?php

return [
    [
        'name' => 'list',
        'type' => 'textfield',
        'value' => 'default',
    ],
    [
        'name' => 'fields',
        'type' => 'textfield',
        'value' => 'price,article,vendor.name,made_in,color,size',
    ],
    [
        'name' => 'fieldsToExclude',
        'type' => 'textfield',
        'value' => 'id,old_price,image,thumb,new,popular,favorite,source',
    ],
    [
        'name' => 'fieldsToShowIfEmpty',
        'type' => 'textfield',
        'value' => '',
    ],
    [
        'name' => 'tpl',
        'type' => 'textfield',
        'value' => 'ms2compare.table',
    ],
    [
        'name' => 'tplEmpty',
        'type' => 'textfield',
        'value' => '@INLINE {\'ms2compare_is_empty\' | lexicon}',
    ],
];
