<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Curs;

class Category extends Model {
   
    protected $table = 'categories';

    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
    ];

    protected $fillable = [
        'id',
        'name',
        'type',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    function cursuri() {
        return $this->hasMany(Curs::class, 'category_id');
    }

    public static function getItems($input, $type = NULL) {
        if(! $type )
        {
            return (new GetItems($input, self::query(), __CLASS__))->Perform();
        }

        return (new GetItems(
            $input, 
            self::query()->where('type', $type)->withCount($type), 
            __CLASS__
        ))->Perform();
    }

}