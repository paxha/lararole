# Introduction

**Lararole** is a Laravel library, provides **Role Management** System with **roles** and **permissions**.
Basically this library provides Modules e.g. Sidebar or Navbar. This is a basic and manageable structure of application.

Lararole is accessible, powerful, and provides tools required for large, robust applications.

Each module belongs to any role and that role has read or write permission.
User can't visit module any page without any permission. Even Without write permission User can't perform any action like create, update or delete.
These permissions are controlled by middleware `permission.read` and `permission.write`.

## How It Works

-   Create Role
-   Assign modules or nested modules to that Role with read/write permission
-   Assign that role to User
-   Enjoy!

## Features

**A Quick Start Project**
-   Nth Level Modules (Sidebar/Navbar)
-   Read/Write Permission Middlewares
-   Directory Structure
-   Module Model
-   Role Model
