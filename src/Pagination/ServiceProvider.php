<?php 

namespace dees040\Pagination;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * This will be used to register config & view in 
     * your package namespace.
     * 
     * @var  string
     */
    protected $packageName = 'pagination';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish your config
        $this->publishes([
            __DIR__ . '/../config/pagination.php' => config_path($this->packageName.'.php'),
        ], 'config');

        $macro = function ($perPage = null, $columns = ['*'], $pageName = 'page', $page = null) {
            $paginator = new Paginator($this, $perPage, $columns, $pageName, $page);

            return $paginator->handle();
        };

        Builder::macro(config('pagination.method'), $macro);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/pagination.php', $this->packageName
        );
    }
}
