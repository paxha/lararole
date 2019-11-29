# Configuration

## Vendor Publish

Before start development need lararole configuration file. for this publish vendor provider. lararole needs some configuration about project which will define in configuration file.

    php artisan vendor:publish --provider="Lararole\LararoleServiceProvider"
    
This will export a file name lararole.php in you config folder. In this file you can setup you modules with nesting sub modules. Example modules and sub modules are exported by default.
There are also providers setup. for basic there only need to define What is your users table Model.

## Modules Array

```php
'modules' => [
    [
        'name' => 'Product',
        'icon' => 'feather icon-layers',
        'modules' => [
            [
                'name' => 'Inventory',
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
```

Add modules with nested sub modules.

## Providers Array

```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\User::class,
    ],
],
```

Add users table Model with use namespace.
