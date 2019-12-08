---
title: Lararole How to use
lang: en-US
date: 12-08-2019
author: Hassan Raza Pasha
meta:
  - name: Lararole How to use
    content: Lararole is a Laravel library, provides Role Management with permissions. Basically this library provides a basic structure of application and instructions to use it. Using this manageable structure you can build large and robust applications.Lararole is accessible, powerful, and provides tools required for large, robust applications. Each module belongs to any role and that role has read or write permission. User can't visit module any page without any permission. Even Without write permission User can't perform any action like create, update or delete. These permissions are controlled by middleware permission.read and permission.write.
  - name: keywords
    content: lararole, laravel role management, laravel user management, laravel library, laravel package, laravel management system
  - name: author
	content: Hassan Raza Pasha
---

# How to Use?

Use trait of `HasRoles`

```php
class User extends Authenticatable
{
    use \Lararole\Traits\HasRoles;
}
```

## Assigning Roles

```php
// Getting User
$user = User::find($id);

/*many to many relationships*/
// Assigning role to user
$user->roles()->sync([
    [
        'role_id' => 2
    ],
    [
        'role_id' => 4
    ],
]);
```