<?php 

return [
    
    /*
    |--------------------------------------------------------------------------
    | Pagination method name.
    |--------------------------------------------------------------------------
    |
    | The method name which you want to use for pagination. Default is
    | dynamicPagination. If you want you override the method name.
    |
    */

    'method' => 'dynamicPagination',

    /*
    |--------------------------------------------------------------------------
    | Force pagination.
    |--------------------------------------------------------------------------
    |
    | If the page parameter is not found in the GET variable the results will
    | not be paginated. If you do not want this default behaviour you can
    | change this variable to true. If this is set to true the results will
    | always be paginated. Even is the page parameter is not found, it then
    | will be set to 1.
    |
    */

    'force_pagination' => false,

];
