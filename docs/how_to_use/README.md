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