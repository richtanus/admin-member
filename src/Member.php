<?php

namespace GG\Admin\Member;

use Encore\Admin\Admin;
use Encore\Admin\Extension;

class Member extends Extension
{
    /**
     * Load configure into laravel from database.
     *
     * @return void
     */
	/*
    public static function load()
    {
        foreach (ConfigModel::all(['name', 'value']) as $config) {
            config([$config['name'] => $config['value']]);
        }
    }
	*/

    /**
     * Bootstrap this package.
     *
     * @return void
     */
    public static function boot()
    {
        static::registerRoutes();

        Admin::extend('member', __CLASS__);
    }

    /**
     * Register routes for laravel-admin.
     *
     * @return void
     */
    protected static function registerRoutes()
    {
        parent::routes(function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $router->resource('useradmin/member', 'GG\Admin\Member\Controllers\MemberController');
            $router->resource('useradmin/permissions', 'GG\Admin\Member\Controllers\PermissionController');
            $router->resource('useradmin/roles', 'GG\Admin\Member\Controllers\RoleController');
            $router->resource('useradmin/menu', 'GG\Admin\Member\Controllers\MenuController');

        });
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('Member', 'member', 'fa-user');

        parent::createPermission('Admin Member', 'ext.member', 'member*');
    }
}