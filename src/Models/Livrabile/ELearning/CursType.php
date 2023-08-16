<?php

namespace MyDpo\Models\Livrabile\ELearning;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Curs;

class CursType extends Model {
   
    protected $table = 'cursuri-types';

    protected $casts = [
        'id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'props',
    ];

    function cursuri() {
        return $this->hasMany(Curs::class, 'type', 'slug');
    }

    public static function getItems($input, $type = NULL) {
        return (new GetItems($input, self::query()->withCount('cursuri'), __CLASS__))->Perform();
    }

}