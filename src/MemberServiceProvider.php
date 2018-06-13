<?php

namespace GG\Admin\Member;

use Illuminate\Support\ServiceProvider;

class MemberServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
		/*
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
		*/
		
        Member::boot();
    }
}
