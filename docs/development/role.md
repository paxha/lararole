# Role

## New Role

```php
// Creating Role
$role = Role::create([
    'name' => 'Role Name'
])
```

## Assign Module with Permission

```php
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
