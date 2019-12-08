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

# Role Model

## Relationships

-   `users()`: The role user.
-   `modules()`: The modules assigned to role with permission.

```php
/*many to many relationship*/
$users = \Lararole\Models\Role::find($id)->users;

/*many to many relationship*/
$modules = \Lararole\Models\Role::find($id)->modules;
```

## How to

This is how to create role and implement in project.

```php
// Creating Role
$role = \Lararole\Models\Role::create([
    'name' => 'Role Name'
]);

// Assigning module with permission to role
$role->modules()->sync([
    [
        'module_id' => 1,
        'permission' => 'write'
    ],
    [
        'module_id' => 4,
        'permission' => 'read'
    ],
    [
        'module_id' => 7,
        'permission' => 'read'
    ],
]);
```
