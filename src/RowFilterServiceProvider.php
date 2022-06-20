<?php

namespace Huozi\Admin\Filter;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Filter;
use Illuminate\Support\ServiceProvider;

class RowFilterServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(RowFilter $extension)
    {
        if (! RowFilter::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'laravel-admin-ext-filter');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/huo-zi/laravel-admin-ext-filter')],
                'laravel-admin-ext-filter'
            );
        }

        Admin::booting(function () {
            Filter::extend('row', Row::class);
        });
    }
}