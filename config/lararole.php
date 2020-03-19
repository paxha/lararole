<?php

return [
    'modules' => [
        [
            'name' => 'Inventory',
            'icon' => 'icon-inventory',
            'modules' => [
                ['name' => 'Product'],
                ['name' => 'Brand'],
                ['name' => 'Category'],
                ['name' => 'Unit'],
                ['name' => 'Attribute'],
            ],
        ],
        [
            'name' => 'User Management',
            'icon' => 'icon-user',
            'modules' => [
                [
                    'name' => 'User',
                    'icon' => 'icon-user',
                ],
                [
                    'name' => 'Role',
                    'icon' => 'icon-role',
                ],
            ],
        ],
        [
            'name' => 'Order Processing',
            'icon' => 'icon-order',
            'modules' => [
                ['name' => 'New Orders'],
                ['name' => 'Dispatched'],
            ],
        ],
        [
            'name' => 'Others',
            'icon' => 'icon-others',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],
    ],

    'attach_all_children' => true,
];
