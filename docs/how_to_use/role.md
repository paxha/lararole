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
