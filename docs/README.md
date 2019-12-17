---
lang: en-US
date: 12-08-2019
author: Hassan Raza Pasha
meta:
  - name: Lararole
    content: Lararole is a Laravel library, provides Role Management with permissions. Basically this library provides a basic structure of application and instructions to use it. Using this manageable structure you can build large and robust applications.Lararole is accessible, powerful, and provides tools required for large, robust applications. Each module belongs to any role and that role has read or write permission. User can't visit module any page without any permission. Even Without write permission User can't perform any action like create, update or delete. These permissions are controlled by middleware permission.read and permission.write.
  - name: keywords
    content: lararole, laravel role management, laravel user management, laravel library, laravel package, laravel management system
home: true
heroImage: /images/logo.png
tagline: 'Lararole is a Laravel library, provides Role Management with permissions.'
actionText: Get Started →
actionLink: /guide/
features:
- title: Simplicity First
  details: Minimal setup with markdown-centered project structure helps you focus on writing.
- title: Lararole Powered
  details: This library provides a basic structure of application and instructions to use it. Using this manageable structure you can build large and robust applications.
- title: Performant
  details: Lararole is accessible, powerful, and provides tools required for large, robust applications.
footer: GNU LGPLv3 Licensed | Copyright © 2019-Present Hassan Raza Pasha
---

::: warning PLEASE NOTE
  This package will assume you are already using laravel authentication system and you have already users table in your database.
:::

## Installation

    composer install paxha/lararole
    
## Publish Configuration File

Before start development, lararole need configurations which will define Modules and User provider.

    php artisan vendor:publish --provider="Lararole\LararoleServiceProvider"
    
This will export `lararole.php` in config. Setup modules with nesting modules. Example modules and nested modules are exported by default.
There are also providers setup.