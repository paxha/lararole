<?php

return [
    'modules' => [
        [
            'name' => 'Product',
            'icon' => 'feather icon-layers',
            'modules' => [
                ['name' => 'Inventory'],
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
                ['name' => 'New'],
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
