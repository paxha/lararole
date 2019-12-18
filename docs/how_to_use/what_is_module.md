---
lang: en-US
date: 12-08-2019
author: Hassan Raza Pasha
meta:
  - name: Lararole Module Model
    content: Lararole is a Laravel library, provides Role Management with permissions. Basically this library provides a basic structure of application and instructions to use it. Using this manageable structure you can build large and robust applications.Lararole is accessible, powerful, and provides tools required for large, robust applications. Each module belongs to any role and that role has read or write permission. User can't visit module any page without any permission. Even Without write permission User can't perform any action like create, update or delete. These permissions are controlled by middleware permission.read and permission.write.
  - name: keywords
    content: lararole, laravel role management, laravel user management, laravel library, laravel package, laravel management system
---

# What is module

Module is like a specific portion contains specific CRUDs like a `Inventory` is module and it has CRUD related `Brand`, `Category` and `Product`.' 
Lararole has Module model which provide some relationships and functions etc...

## Relationships

The Module provides various relationships:

-   `children()`: The model's direct children.
-   `nestedChildren()`: The model's nested children.
-   `parent()`: The model's direct parent.
-   `nestedParents()`: The model's nested parents by object.

```php
$modules = Module::with('children')->get();

$modules = Module::with('nestedChildren')->get();

$modules = Module::with('parent')->get();

$modules = Module::with('nestedParents')->get();
```

## Scopes

The trait provides query scopes to filter models by their position in the tree:

-   `hasChildren()`: Models with children.
-   `hasParent()`: Models with a parent.
-   `leaf()`: Models without children.
-   `root()`: Models without a parent.

```php
$noLeaves = Module::hasChildren()->get();

$noRoots = Module::hasParent()->get();

$leaves = Module::leaf()->get();

$roots = Module::root()->get();
```

## Functions

The trait provides helper functions:

-   `descendents()`: The model's all Children in single array.
-   `ancestors()`: The model's all parents in single array.
-   `siblings()`: The parent's other children.

```php
$descendents = Module::find($id)->descendents();

$ancestors = Module::find($id)->ancestors();

$siblings = Module::find($id)->siblings();
```
