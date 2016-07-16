<?php
return [
    'user' => [
        'type' => 1,
        'ruleName' => 'userGroup',
    ],
    'manager' => [
        'type' => 1,
        'ruleName' => 'userGroup',
        'children' => [
            'user',
        ],
    ],
    'admin' => [
        'type' => 1,
        'ruleName' => 'userGroup',
        'children' => [
            'manager',
        ],
    ],
];
