---
lang: en-US
date: 12-08-2019
author: Hassan Raza Pasha
meta:
  - name: Lararole How to use
    content: Lararole is a Laravel library, provides Role Management with permissions. Basically this library provides a basic structure of application and instructions to use it. Using this manageable structure you can build large and robust applications.Lararole is accessible, powerful, and provides tools required for large, robust applications. Each module belongs to any role and that role has read or write permission. User can't visit module any page without any permission. Even Without write permission User can't perform any action like create, update or delete. These permissions are controlled by middleware permission.read and permission.write.
  - name: keywords
    content: lararole, laravel role management, laravel user management, laravel library, laravel package, laravel management system
---

# How to Assign Role?

Use trait of `HasRoles`

```php
class User extends Authenticatable
{
    use \Lararole\Traits\HasRoles;
}
```

## Helper Functions

-   `assignRoles(array $roles)` To assign roles.
-   `removeRoles(array $roles)` To remove roles.
-   `removeAllRoles()` To remove all roles.

### Assigning Roles

```php
$user = User::find($id);

/*many to many relationships*/
// Assigning role to user
$user->roles()->assignRoles([2, 4]);

// Alternative
$user->roles()->sync([
    [
        'role_id' => 2
    ],
    [
        'role_id' => 4
    ],
]);
```