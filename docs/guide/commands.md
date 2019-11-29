# Commands

## Migrate

To migrate lararole database use migrate
    
    php artisan migrate

## DB Seed

For development generate dummy roles with some modules.
In your **DatabaseSeeder.php** run method call LararoleSeeder class.

```php
$this->call(\Lararole\Database\Seeds\LararoleSeeder::class);
```

After seeder setup run

    php artisan db:seed

## Super Admin Role

To create a super admin role which has every module write access.

    php artisan make:super-admin-role

## Make Views

To generate basic blade views with exact directory path run

    php artisan make:views
