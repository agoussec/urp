<?php

namespace Agoussec\URP\Models;

use Agoussec\URP\Models\Role;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_permissions',  'permission_id', 'role_id');
    }

    public function users()
    {
        return $this->belongsToMany(config("auth.providers.users.model"), 'users_permissions');
    }
}
