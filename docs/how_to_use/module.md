# Module Model

## Relationships

-   `ancestors()`: The module recursive parents.
-   `ancestorsAndSelf()`: The module recursive parents and itself.
-   `children()`: The module direct children.
-   `childrenAndSelf()`: The module direct children and itself.
-   `descendants()`: The module recursive children.
-   `descendantsAndSelf()`: The module recursive children and itself.
-   `parent()`: The module direct parent.
-   `parentAndSelf()`: The module direct parent and itself.
-   `siblings()`: The parent's other children.
-   `siblingsAndSelf()`: All the parent's children.

```php
$ancestors = \Lararole\Models\Module::find($id)->ancestors;

$users = \Lararole\Models\Module::with('descendants')->get();

$users = \Lararole\Models\Module::whereHas('siblings', function ($query) {
    $query->where('name', '=', 'Product');
})->get();

$total = \Lararole\Models\Module::find($id)->descendants()->count();
```

### Tree

```php
$tree = \Lararole\Models\Module::tree()->get();
```

### Filters

-   `hasChildren()`: Models with children.
-   `hasParent()`: Models with a parent.
-   `isLeaf()`: Models without children.
-   `isRoot()`: Models without a parent.

```php
$noLeaves = \Lararole\Models\Module::hasChildren()->get();

$noRoots = \Lararole\Models\Module::hasParent()->get();

$leaves = \Lararole\Models\Module::isLeaf()->get();

$roots = \Lararole\Models\Module::isRoot()->get();
```

### Order

-   `breadthFirst()`: Get siblings before children.
-   `depthFirst()`: Get children before siblings.

```php
$tree = \Lararole\Models\Module::tree()->breadthFirst()->get();

$descendants = \Lararole\Models\Module::find($id)->descendants()->depthFirst()->get();
```

## Other Relationships

-   `roles()`: The module roles in which this module is attached.

```php
/*it will return Roles with permission*/
$roles = \Lararole\Models\Module::find($id)->roles;
```

-   `users()`: The module users through roles.

```php
/*it will return User array with permission of this module*/
$users = \Lararole\Models\Module::find($id)->users;
```

## Functions

-   `module_users()`: The module users through roles.

```php
/*it will return all the admin and simple User array without permission*/
$all_users = \Lararole\Models\Module::find($id)->module_users();
```