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
            $router->resource('member', 'GG\Admin\Member\MemberController');
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('Member', 'config', 'fa-user');

        parent::createPermission('Admin Member', 'ext.member', 'member*');
    }
}