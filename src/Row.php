<?php

namespace Huozi\Admin\Filter;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Filter\AbstractFilter;

class Row extends AbstractFilter
{

    /**
     * @var Filter
     */
    private $filter;

    private $callback;

    private $values;

    /**
     * Where constructor.
     *
     * @param string   $label
     * @param \Closure $query
     */
    public function __construct($label, \Closure $callback)
    {
        $this->label = $label;
        $this->callback = $callback;

        $this->setPresenter(new RowPresenter);
    }

    public function setParent(Filter $filter)
    {
        parent::setParent($filter);

        $this->filter = new Filter((function() {
            return $this->model;
        })->call($this->parent));
        $this->filter->removeFilterByID('id');

        call_user_func($this->callback, $this->filter);
    }

    private function setupScript()
    {
        Admin::style(<<<STYLE
.group-row {
    display: flex;
    flex-wrap:wrap;
}
.group-row .form-group {
    flex: 1;
    margin-right: 6px;
    margin-bottom: 0;
}
.group-row .form-group label {
    display: none;
}
.group-row .form-group .col-sm-8 {
    width:100% !important;
    padding-right: 0 !important;
}
STYLE
        );
    }

    public function condition($inputs)
    {
        $this->values = array_reduce($this->filter->filters(), function($values, $filter) use ($inputs) {
            if ($condition = $filter->condition($inputs)) {
                foreach($condition as $key => $value) {
                    $values[$key][] = $value;
                }
            }
            return $values;
        });

        if(!$this->values) {
            return;
        }

        return [
            'where' => [
                function ($query) {
                    array_map(function ($method, $values) use ($query) {
                        foreach ($values as $value) {
                            call_user_func_array([$query, $method], $value);
                        }
                    }, array_keys($this->values ?? []), $this->values ?? []);
                }
            ]
        ];
    }

    protected function variables()
    {
        $this->id = 'distpicker-filter-' . uniqid();

        $this->setupScript();
        return array_merge([
            'id'        => $this->id,
            'name'      => $this->formatName($this->label),
            'label'     => $this->label,
            'filters'   => $this->filter->filters(),
            'presenter' => $this->presenter(),
        ], $this->presenter()->variables());
    }

    /**
     * {@inheritdoc}
     */
    public function getColumn()
    {
        $columns = [];

        $parentName = $this->parent->getName();

        foreach ($this->filter->filters() as $filter) {
            $column = $filter->getColumn();
            $columns[] = $parentName ? "{$parentName}_{$column}" : $column;
        }

        return $columns;
    }
}