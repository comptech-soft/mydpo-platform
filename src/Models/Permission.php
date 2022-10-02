<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Helpers\Performers\Datatable\GetItems;
// https://github.com/lazychaser/laravel-nestedset#installation

class Permission extends Model {
    use NodeTrait;
    
    protected $table = 'permissions';

    // protected $with = ['ancestors'];

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

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

}
