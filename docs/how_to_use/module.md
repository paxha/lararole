---
title: Lararole Module Model
lang: en-US
date: 12-08-2019
author: Hassan Raza Pasha
meta:
  - name: Lararole Module Model
    content: Lararole is a Laravel library, provides Role Management with permissions. Basically this library provides a basic structure of application and instructions to use it. Using this manageable structure you can build large and robust applications.Lararole is accessible, powerful, and provides tools required for large, robust applications. Each module belongs to any role and that role has read or write permission. User can't visit module any page without any permission. Even Without write permission User can't perform any action like create, update or delete. These permissions are controlled by middleware permission.read and permission.write.
  - name: keywords
    content: lararole, laravel role management, laravel user management, laravel library, laravel package, laravel management system
  - name: author
	content: Hassan Raza Pasha
---

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