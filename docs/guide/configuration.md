---
lang: en-US
date: 12-08-2019
author: Hassan Raza Pasha
meta:
  - name: Lararole Configuration
    content: Lararole is a Laravel library, provides Role Management with permissions. Basically this library provides a basic structure of application and instructions to use it. Using this manageable structure you can build large and robust applications.Lararole is accessible, powerful, and provides tools required for large, robust applications. Each module belongs to any role and that role has read or write permission. User can't visit module any page without any permission. Even Without write permission User can't perform any action like create, update or delete. These permissions are controlled by middleware permission.read and permission.write.
  - name: keywords
    content: lararole, laravel role management, laravel user management, laravel library, laravel package, laravel management system
---

# Configuration

## Migrations

Lararole provides role management db structure. Your application must migrate it before use it.

- 2019_11_17_000000_create_modules_table
- 2019_11_17_100000_create_roles_table
- 2019_11_17_200000_create_module_role_table
- 2019_11_17_300000_create_role_user_table

To migrate these migrations.
    
    php artisan migrate
    
### ER Diagram

![ER Diagram](../.vuepress/public/images/erd.png)

## Make Views

To generate basic blade views with exact directory path run

    php artisan make:views
    
All the views will generate in specific folders with sequence of module and nested modules like this.

### Views Directory Structure

```
modules
│
└───Module
│   │
│   └───Child Module
│       │   create.blade.php
│       │   edit.blade.php
│       │   index.blade.php
│       │   show.blade.php
│
└───Module
│   │
│   └───Child Module
│   │    │
│   │    └───Child Module
│   │        │   create.blade.php
│   │        │   edit.blade.php
│   │        │   index.blade.php
│   │        │   show.blade.php
│   │
│   └───Child Module
│       │   create.blade.php
│       │   edit.blade.php
│       │   index.blade.php
│       │   show.blade.php
│
└───Module
│   │   create.blade.php
│   │   edit.blade.php
│   │   index.blade.php
│   │   show.blade.php
```

## Make Super Admin Role

To create a super admin role which has every module write access.

    php artisan make:super-admin-role
    
## Assign Super Admin Role to User

To assign super admin role to any user by user id.

    php artisan assign-super-admin-role --user={$id}
    
## Seeder

For development generate dummy roles with some modules.
Call LararoleSeeder in `DatabaseSeeder.php` class.

```php
$this->call(\Lararole\Database\Seeds\LararoleSeeder::class);
```

After seeder setup run

    php artisan db:seed
    
## Routes

There are default 4 routes 
- `module.index` to show index page.
- `module.create` to show create page.
- `module.show` to show show page.
- `module.edit` to show edit page.

```php
Route::get('module/{module_slug}', 'Controller@index')->name('module.index');
Route::get('module/{module_slug}/create', 'Controller@create')->name('module.create');
Route::get('module/{module_slug}/{module}', 'Controller@show')->name('module.show');
Route::get('module/{module_slug}/{module}/edit', 'Controller@edit')->name('module.edit');
```

### How to use routes?

```php
route('module.index', $module->slug);
route('module.create', $module->slug);
route('module.show', [$module->slug, $product->id]); // Here product is object of Product model. you have to send product id to this route. 
route('module.edit', [$module->slug, $product->id]); // same here, id required of Any Model
```

#### How to use id

In views folder `show.blade.php` or `edit.blade.php` controller will return back that `id` back.

##### Example of use id in blade

```blade
@section('content')
@php
    $object = YourModel::find($id);
@endphp

{{--Example of use in edit.blade.php--}}
<input type="text" name="name" value="{{ old('name') ?? $object->name }}">
@endsection
```