<?php

namespace dees040\Pagination\Tests;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'weight',
    ];
}
