<?php

return [
    'modules' => [
        [
            'name' => 'Product',
            'icon' => 'feather icon-layers',
            'modules' => [
                [
                    'name' => 'Inventory',
                    'modules' => [
                        ['name' => 'SKU'],
                    ],
                ],
                ['name' => 'Brand'],
                ['name' => 'Category'],
                ['name' => 'Unit'],
                ['name' => 'Attribute'],
            ],
        ],
        [
            'name' => 'User Management',
            'icon' => 'feather icon-user',
            'modules' => [
                ['name' => 'User'],
                ['name' => 'Role'],
            ],
        ],
        [
            'name' => 'Order Processing',
            'icon' => 'feather icon-settings',
            'modules' => [
                ['name' => 'Search'],
                [
                    'name' => 'New',
                    'modules' => [
                        ['name' => 'New Order'],
                    ],
                ],
                ['name' => 'Dispatched'],
                ['name' => 'Delivered'],
                ['name' => 'Cancelled'],
            ],
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],
    ],
];
