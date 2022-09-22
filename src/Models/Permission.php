<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

// https://github.com/lazychaser/laravel-nestedset#installation

class Permission extends Model {
    use NodeTrait;
    
    protected $table = 'permissions';

    protected $with = ['ancestors'];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'platform',
        'description',
        'order_no',
        'props',
        'deleted',
        'created_by',
        'updated_by',
    ];

}
