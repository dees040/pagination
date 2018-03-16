# Dynamic pagination in Laravel

Dynamic pagination for your Eloquent models. Paginate, order and sort with just the query parameters. 

## Installation

Use composer to install the package.

```bash
composer require dees040/pagination
```

The service provider will automatically by registered using Laravel 5.5 or higher. Otherwise add the following provider to your `providers` array in the `config/app.php`

```php
dees040\Pagination\ServiceProvider
``` 

The package comes with a small config file. To publish the config file run the following command:

```bash
php artisan vendor:publish --provider="dees040\Pagination\ServiceProvider" --tag="config"
```

## Usage

The package will add a macro (method) to the Eloquent builder automatically when the service provider is registered. You can then use the `dynamicPagination()` method. If you like you can chance the method name in the config.

Example:

```php
<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('age', '>', 18)
            ->dynamicPagination();
        
        return response()->json($users);
    }
}

``` 

The `dynamicPagination()` has the same signature as the [`paginate()`](https://laravel.com/api/5.6/Illuminate/Database/Eloquent/Builder.html#method_paginate) method from the Eloquent builder:

```php
dynamicPagination($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)`
```

All these parameters are optional and if some are not given it will try and read them from the query (GET) parameters.

### Parameter options

**`page`**

The page parameter is the main parameter. If the `page` parameter is not present in the query (GET) parameters the results will not be paginated. Unless the config value `force_pagination` is set to `true`. The page parameters represents the page which needs to be paginated.

**`per_page`**

The per page parameter represents the amount of items that needs to be paginated per page. So if the value is 15, the paginator will return 15 results. If the parameter is not found, the paginator will use the [default value](https://github.com/laravel/framework/blob/5.6/src/Illuminate/Database/Eloquent/Model.php#L83) set in the model, which can be overwritten in the model.

**`order_by`**

If you'd like you can execute an `order by` before you paginate the results. This comes in handy if you are using an API and you can order the results in a table in your frontend application. The paginator checks if you can use the given key for ordering. It checks if the method `getOrderableKeys()` exists in the model. This method should return an array of fields (strings) which can be orderd on. If the `getOrderableKeys()` doesn't exists it will use the `$fillable` items. The `id` key will always be added. 

**`sort_by`**

In what way do you want to your results to be ordered? `asc` or `desc`? Specify it with this parameter option. This can only be used with the `order_by` parameter.

#### Example route

An example if all parameters are used:

`https://example.com/posts?page=3&per_page=30&order_by=updated_at&sort_by=desc`

Here you will get 30 posts starting on page 3. The results are ordered by the `updated_at` field, descending.

## TODO

- Order by relations
- Add options to search
  - Using scout
- Add support with macros
  - For collections
  - For Query Builder
- Order by multiple fields