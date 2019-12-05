# Introduction

**Lararole** is a Laravel library, provides **Role Management** System with **roles** and **permissions**.
Basically this library provide Modules e.g. Sidebar or Navbar. A basic structure of application is developed and well managed.
Easy to integrate modules using lararole structure. 

**Lararole is accessible, powerful, and provides tools required for large, robust applications.**

Each module belongs to any role and that role has read or write permission.
Without write permission logged in user can't perform any action like create, update or delete.
these permissions are controlled by middleware `permission.read` and `permission.write`.

Logged in user can't visit module index page without any permission.

## How It Works
