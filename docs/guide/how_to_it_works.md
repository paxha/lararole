# How It Works?

### Database

Lararole provides role management db structure. Your application must migrate it before use it.

#### ER Diagram

![An image](../.vuepress/public/images/erd.png)

#### Migrations

- 2019_11_17_000000_create_modules_table
- 2019_11_17_100000_create_roles_table
- 2019_11_17_200000_create_module_role_table
- 2019_11_17_300000_create_role_user_table

#### Models

- Module
- Role

To migrate these migrations tables.
    
    php artisan migrate

### DB Seed

For development generate dummy roles with some modules.
Call LararoleSeeder in `DatabaseSeeder.php` class.

```php
$this->call(\Lararole\Database\Seeds\LararoleSeeder::class);
```

After seeder setup run

    php artisan db:seed

### Super Admin Role

To create a super admin role which has every module write access.

    php artisan make:super-admin-role

### Make Views

To generate basic blade views with exact directory path run

    php artisan make:views

All the views will generate in specific folders with sequence of module and nested modules like this.

### Views Directory Structure

```
modules
│
└───Module
│   │
│   └───Child Module
│       │   create.blade.php
│       │   edit.blade.php
│       │   index.blade.php
│       │   show.blade.php
│
└───Module
│   │
│   └───Child Module
│   │    │
│   │    └───Child Module
│   │        │   create.blade.php
│   │        │   edit.blade.php
│   │        │   index.blade.php
│   │        │   show.blade.php
│   │
│   └───Child Module
│       │   create.blade.php
│       │   edit.blade.php
│       │   index.blade.php
│       │   show.blade.php
│
└───Module
│   │   create.blade.php
│   │   edit.blade.php
│   │   index.blade.php
│   │   show.blade.php
```
