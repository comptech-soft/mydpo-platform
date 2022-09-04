<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\Category;

class Centralizator extends Model {

    protected $table = 'centralizatoare';

    protected $with = ['category'];

    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'status',
        'name',
        'category_id',
        'description',
        'subject',
        'body',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public static function getQuery() {
        return 
            self::query()
            ->leftJoin(
                'categories',
                function($j) {
                    $j->on('categories.id', '=', 'chestionare.category_id');
                }
            )
            ->select('chestionare.*')
        ;
    }

    public static function getItems($input) {
        return (new GetItems(
            $input, 
            self::getQuery()->with([
            ]), 
            __CLASS__
        ))
        ->Perform();
    }

}