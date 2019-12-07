# Getting Started

::: warning COMPATIBILITY NOTE
MySQL 8.0+
:::

::: warning NOTE
There must be exist **users** table with its model in project.
:::

## Installation

Install using composer
    
    composer require paxha/lararole
    
## Manual Installation

Add these lines in composer.json file

``` json
"require": {
    "paxha/lararole": "dev-master"
}
```

After adding these lines run composer update

    composer update

## Publish Configuration File

Before start development lararole need configurations which will define Modules and User provider.

    php artisan vendor:publish --provider="Lararole\LararoleServiceProvider"
    
This will export `lararole.php` in config. Setup modules with nesting modules. Example modules and nested modules are exported by default.
There are also providers setup.

## Modules Array

```php
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
];
```

These module are major part of this library these modules these modules could be a sidebar or navbar. User can access only those modules which are assigned him by super admin. these module only be assign through roles.

Note: These modules cannot directly assign to any user, only these can be assign through any role. during registering modules with role, permission also required that what user can do.

## Providers Array

```php
return [
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],
    ],
];
```

Lararole library require User provider because there are some relations need User model and there are directory `many to many` relationship between **User** and **Role**
