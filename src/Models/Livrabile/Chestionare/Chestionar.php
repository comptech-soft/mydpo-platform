<?php

namespace MyDpo\Models\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Livrabile\Category;

class Chestionar extends Model {
    
    protected $table = 'chestionare';

    protected $with = ['category'];

    // protected $withCount = ['intrebari'];

    protected $casts = [
        'id' => 'integer',
        'props' => 'json',
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
        'props',
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


}