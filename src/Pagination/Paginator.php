<?php

namespace dees040\Pagination;

class Paginator
{
    /**
     * The model to perform pagination on.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * The current page.
     *
     * @var int
     */
    protected $page;

    /**
     * The amount of items to paginate per page.
     *
     * @var int
     */
    protected $perPage;

    /**
     * The amount of items to paginate per page.
     *
     * @var int
     */
    protected $columns;

    /**
     * The amount of items to paginate per page.
     *
     * @var int
     */
    protected $pageName;

    /**
     * The field to order by.
     *
     * @var string
     */
    protected $orderBy;

    /**
     * The type of order.
     *
     * @var string
     */
    protected $sortBy;

    /**
     * Paginator constructor.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int  $page
     * @return void
     */
    public function __construct($builder, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->builder = $builder;

        $this->setOptions($perPage, $columns, $pageName, $page);
    }

    /**
     * Handle the pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function handle()
    {
        $this->addOrder();

        if ($this->shouldPaginate() || $this->shouldForcePagination()) {
            return $this->builder->paginate($this->perPage, ['*'], 'page', $this->page);
        }

        return $this->builder->get();
    }

    /**
     * Add an "order by" clause to the query.
     *
     * @return void
     */
    protected function addOrder()
    {
        if ($this->orderBy !== null) {
            $this->builder->orderBy($this->orderBy, $this->sortBy);
        }
    }

    /**
     * Determine if we should force pagination.
     *
     * @return bool
     */
    protected function shouldForcePagination()
    {
        return config('pagination.force_pagination');
    }

    /**
     * Determine is we should paginate the results. Inverse of the
     * shouldntPaginate method.
     *
     * @return bool
     */
    public function shouldPaginate()
    {
        return ! $this->shouldntPaginate();
    }

    /**
     * Determine if we should paginate the results. If we reach this
     * method the constructor is already called. This means the
     * setOptions() method is called and all the field have variables.
     * If the page property is null we should't paginate.
     *
     * @return bool
     */
    public function shouldntPaginate()
    {
        return $this->page === null;
    }

    /**
     * Set all the pagination options.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int  $page
     * @return void
     */
    public function setOptions($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->page = $page ?? request('page');
        $this->perPage = $perPage ?? request('per_page', $this->builder->getModel()->getPerPage());
        $this->columns = $columns;
        $this->pageName = $pageName;
        $this->orderBy = $this->determineOrderBy();
        $this->sortBy = request('sort_by');
    }

    /**
     * Get the orderBy key and determine if it can be used to order.
     *
     * @return string|null
     */
    protected function determineOrderBy()
    {
        $orderBy = request('order_by');

        if (is_null($orderBy)) {
            return null;
        }

        $model = $this->builder->getModel();

        $orderable = method_exists($model, 'getOrderableKeys')
            ? $model->getOrderableKeys()
            : $model->getFillable();

        $orderable[] = 'id';

        return in_array($orderBy, $orderable) ? $orderBy : null;
    }

    /**
     * Get the per page attribute.
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }
}
