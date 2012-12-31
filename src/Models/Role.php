<?php

namespace Agoussec\URP\Models;

use Agoussec\URP\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'slug'
    ];

    public $timestamps = false;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    public function users()
    {
        return $this->belongsToMany(config("auth.providers.users.model"), 'users_roles');
    }



}

