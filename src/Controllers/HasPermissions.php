<?php

namespace GG\Admin\Member\Controllers;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

trait HasPermissions
{
    /**
     * Get avatar attribute.
     *
     * @param string $avatar
     *
     * @return string
     */
    public function getAvatarAttribute($avatar)
    {
        if ($avatar) {
            return Storage::disk(config('admin.upload.disk'))->url($avatar);
        }

        return admin_asset('/vendor/laravel-admin/AdminLTE/dist/img/user2-160x160.jpg');
    }

    /**
     * A user has and belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles() : BelongsToMany
    {
        $pivotTable = config('manager.database.users_roles_map');

        $relatedModel = config('manager.database.roles_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'role_id');
    }

    /**
     * A User has and belongs to many permissions.
     *
     * @return BelongsToMany
     */
    public function permissions() : BelongsToMany
    {
        $pivotTable = config('manager.database.users_permissions_map');

        $relatedModel = config('manager.database.permissions_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'permission_id');
    }

    /**
     * Get all permissions of user.
     *
     * @return mixed
     */
    public function allPermissions() : Collection
    {

        //debugbar()->info($this->roles()->get());
        //debugbar()->info($this->permissions);
        //debugbar()->info('middleware_permission');
        //debugbar()->info($this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->merge($this->myusers));

        //dd($this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->merge($this->permissions));

        //debugbar()->info($this->gavin());

        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->merge($this->permissions);
    }

    /**
     * Check if user has permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function isCan(string $permission) : bool
    {
        if ($this->isAdministrator()) {
            return true;
        }

        if ($this->permissions->pluck('slug')->contains($permission)) {
            return true;
        }

        return $this->roles->pluck('permissions')->flatten()->pluck('slug')->contains($permission);
    }

    /**
     * Check if user has no permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function isCannot(string $permission) : bool
    {
        return !$this->can($permission);
    }

    /**
     * Check if user is administrator.
     *
     * @return mixed
     */
    public function isAdministrator() : bool
    {
        return $this->isRole('administrator');
    }

    /**
     * Check if user is $role.
     *
     * @param string $role
     *
     * @return mixed
     */
    public function isRole(string $role) : bool
    {
        return $this->roles->pluck('slug')->contains($role);
    }

    /**
     * Check if user in $roles.
     *
     * @param array $roles
     *
     * @return mixed
     */
    public function inRoles(array $roles = []) : bool
    {
        return $this->roles->pluck('slug')->intersect($roles)->isNotEmpty();
    }

    /**
     * If visible for roles.
     *
     * @param $roles
     *
     * @return bool
     */
    public function visible(array $roles = []) : bool
    {
        if (empty($roles)) {
            return true;
        }

        debugbar()->info('roles');
        debugbar()->info($roles);


        $roles = array_column($roles, 'slug');

        return $this->inRoles($roles);
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->roles()->detach();

            $model->permissions()->detach();
        });
    }
}
