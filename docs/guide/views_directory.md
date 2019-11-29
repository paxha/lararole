# Views Directory

## Make Views

To generate basic blade views with exact directory path run

    php artisan make:views

All the view will generate in specific folders with sequence of module and nested sub modules like this.

## Views Directory Structure

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