<?php

namespace Lararole\Tests\Models;

use Lararole\Traits\HasRoles;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthorizableContract, AuthenticatableContract
{
    use HasRoles, SoftDeletes, Authorizable, Authenticatable;

    protected $fillable = [
        'name',
    ];
}
