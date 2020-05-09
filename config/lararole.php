<?php

return [
    /*
     * Name should be unique otherwise it will cause error during sync modules you can put your short hand alias for display
     * */
    'modules' => [
        [
            'name' => 'Inventory',
            'icon' => 'icon-inventory',
            'alias' => 'Products',
            'modules' => [
                [
                    'name' => 'Product',
                    'alias' => 'Products',
                ],
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

    'loggable' => false,

    'attachAllChildren' => false,
];
