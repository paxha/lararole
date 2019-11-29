# User

## Trait
Use trait of **HasRoles**
```php
class User extends Model
{
    use HasRoles;
}
```

## Assign Roles

```php
// Getting User
$user = User::find(1);

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
