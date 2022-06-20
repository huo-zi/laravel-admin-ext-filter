laravel-admin extension RowFilter
======
A laravel-admin filter extension that enables multiple filters to be displayed on one row.

## Installation

```bash
composer require huo-zi/laravel-admin-ext-filter
```

## Usage
```php
$filter->row('label_name', function (Filter $filter) {
  $filter->equal('col_foo', 'text_column');
  $filter->equal('col_bar', 'select_column')->select();
  $filter->equal('col_baz', 'time_filter')->date();
});
```
When you use filter presenter `select`, the default `placeholder` is `trans('admin.choose')`, you can use `config` to change it.
```php
$filter->equal('col_bar')->select()->config('placeholder', 'select_label');
```


:rotating_light: Not support filter presenter `radio` `checkbox`.
