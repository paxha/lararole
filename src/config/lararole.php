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
                [
                    'name' => 'Bundle',
                ],
                [
                    'name' => 'Stock Control',
                    'modules' => [
                        ['name' => 'Inventory Count'],
                        ['name' => 'Purchase Request'],
                        ['name' => 'Transfer Stock'],
                    ],
                ],
                ['name' => 'Outlet'],
                ['name' => 'Brand'],
                ['name' => 'Category'],
                ['name' => 'Unit'],
                ['name' => 'Attribute'],
            ]
        ],
        [
            'name' => 'Procurement',
            'icon' => 'feather icon-truck',
            'modules' => [
                ['name' => 'Procurement Request'],
                ['name' => 'Purchase Order'],
                ['name' => 'Supplier'],
            ]
        ],
        [
            'name' => 'User Management',
            'icon' => 'feather icon-user',
            'modules' => [
                ['name' => 'User'],
                ['name' => 'Role'],
            ]
        ],
        [
            'name' => 'Order Processing',
            'icon' => 'feather icon-settings',
            'modules' => [
                ['name' => 'Search',
                    'modules' => [
                        ['name' => 'Add Order']
                    ],
                ],
                ['name' => 'New'],
                ['name' => 'ATS'],
                ['name' => 'In Transit'],
                ['name' => 'AIH'],
                ['name' => 'Dispatched'],
                ['name' => 'Delivered'],
                ['name' => 'Cancelled'],

            ]
        ], [
            'name' => 'Setting',
            'icon' => 'feather icon-settings',
            'modules' => [
                ['name' => 'Loyalty'],
                ['name' => 'Country'],
            ]
        ],
        [
            'name' => 'Others',
            'icon' => 'feather icon-align-justify',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],
    ],
];
