<?php

namespace Lararole\Tests\Models;

use Lararole\Traits\HasRoles;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Model
{
    use HasRoles, SoftDeletes, Authorizable, Authenticatable;
}
