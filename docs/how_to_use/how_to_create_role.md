---
lang: en-US
date: 12-08-2019
author: Hassan Raza Pasha
meta:
  - name: Lararole Role Model
    content: Lararole is a Laravel library, provides Role Management with permissions. Basically this library provides a basic structure of application and instructions to use it. Using this manageable structure you can build large and robust applications.Lararole is accessible, powerful, and provides tools required for large, robust applications. Each module belongs to any role and that role has read or write permission. User can't visit module any page without any permission. Even Without write permission User can't perform any action like create, update or delete. These permissions are controlled by middleware permission.read and permission.write.
  - name: keywords
    content: lararole, laravel role management, laravel user management, laravel library, laravel package, laravel management system
---

# How to create role

::: tip
  This library is providing role facades for easy implementation.
:::

## Relationships

-   `users()`: The role user.
-   `modules()`: The modules assigned to role with permission.

```php
/*many to many relationship*/
$users = role()->find($id)->users;

/*many to many relationship*/
$modules = role()->find($id)->modules;
```

## Helper Functions

-   `assignModules(Request $request)` To assign modules with permission. It takes Request object
-   `removeModules(array $modules)` To remove modules from role.
-   `removeAllModules()` To remove all modules.

### How to use

This is how to create role and assigning modules.

::: warning PLEASE NOTE
  If you are going to assign each module in parent module you just need to send parent module id, children ids will automatically assign against role.
:::

```php
// Creating Role
$role = role()->create([
    'name' => 'Role Name'
]);

// Create request
$modules[0]['module_id'] = module()->all()->where('slug', 'product')->first()->id;
$modules[0]['permission'] = 'read';
$modules[1]['module_id'] = module()->all()->where('slug', 'user_management')->first()->id;
$modules[1]['permission'] = 'write';
        
$request = new Request([
    'modules' => $modules,
]);

// Assigning modules with permission to role
$role->assignModules($request);

// Alternative of assigning modules
$role->modules()->attach([
    [
        'module_id' =>  module()->all()->where('slug', 'product')->first()->id,
        'permission' => 'write'
    ],
    [
        'module_id' => module()->all()->where('slug', 'settings')->first()->id,
        'permission' => 'read'
    ],
    [
        'module_id' => module()->all()->where('slug', 'others')->first()->id,
        'permission' => 'read'
    ],
]);
```

## Facade Functions

-   `role()->create($name)` To create a new role.
-   `role()->all()` To get all roles.
-   `role()->find($id)` To find a role by id.
-   `role()->trashed($id = null)` To find from trashed role by id or all trashed role.
-   `role()->withTrashed($id = null)` To find from all with trashed role by id or all roles with trashed.