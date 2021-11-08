<?php

namespace Agoussec\URP\Traits;

use Agoussec\URP\Models\Permission;
use Agoussec\URP\Models\Role;

trait HasPermissions
{


    /**
     * Give permission to any permissions
     *
     * @param  array  $permissions
     * @return $this
     */
    public function givePermissionsTo(...$permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        if ($permissions === null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }


    /**
     * Withdraw user permission
     *
     * @param  array  $permissions
     * @return  $this
     */
    public function withdrawPermissionsTo(...$permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }


    /**
     * Refresh permission of user
     *
     * @param  array  $permissions
     * @return  $this
     */
    public function refreshPermissions(...$permissions)
    {
        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }


    /**
     * Check whether user has specific permission
     *
     * @param  \Illuminate\Database\Eloquent\Model  $permission
     * @return  boolean
     */
    public function hasPermissionTo($permission)
    {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }


    /**
     * Check permission through user role
     *
     * @param  \Illuminate\Database\Eloquent\Model  $permission
     * @return  \Illuminate\Database\Eloquent\Model|static
     */
    public function hasPermissionThroughRole($permission)
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }


    /**
     * Check whether user has role
     *
     * @param  array  $roles
     * @return  bool
     */
    public function hasRole(...$roles)
    {
        foreach ($roles as $r1) {
            if (is_array($r1)) {
                foreach ($r1 as $r2) {
                    if ($this->roles->contains('slug', $r2)) {
                        return true;
                    }
                }
            } else {
                if ($this->roles->contains('slug', $r1)) {
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * Create a new command instance.
     *
     * @return  \Illuminate\Database\Eloquent\Model|static
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }


     /**
     * Create a new command instance.
     *
     * @param  string  $roleslug
     * @return  \Illuminate\Database\Eloquent\Model|static
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }


    /**
     * Check whether user has permission
     *
     * @param  \Illuminate\Database\Eloquent\Model  $permission
     * @return  \Illuminate\Database\Eloquent\Model|static
     */
    protected function hasPermission($permission)
    {
        return (bool) $this->permissions->where('slug', $permission->slug)->count();
    }


    /**
     * Get all permission given to user
     *
     * @param  array  $permissions
     * @return  \Illuminate\Database\Eloquent\Model|static
     */
    protected function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('slug', $permissions)->get();
    }
}
