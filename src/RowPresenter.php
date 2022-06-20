<?php

namespace Huozi\Admin\Filter;

use Encore\Admin\Grid\Filter\Presenter\Presenter;

class RowPresenter extends Presenter
{
    public function view() : string
    {
        return 'laravel-admin-ext-filter::row';
    }
}