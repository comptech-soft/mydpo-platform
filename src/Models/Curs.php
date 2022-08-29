<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\Category;

class Curs extends Model {

    protected $table = 'cursuri';

    protected $with = ['category'];

    // protected $withCount = ['fisiere'];

    protected $casts = [
        'id' => 'integer',
        'props' => 'json',
        'category_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'tematica' => 'json',
        'file' => 'json',
        'k_avatar' => 'json',
    ];

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'type',
        'descriere',
        'tematica',
        'url',
        'date_from',
        'date_to',
        'props',
        'file',
        'k_id',
        'k_level',
        'k_duration',
        'k_number_students_enrolled',
        'k_from_training_tracker',
        'k_avatar',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // public function customercursuri() {
    //     return $this->hasMany(CustomerCurs::class, 'curs_id');
    // }

    // public function fisiere() {
    //     return $this->hasMany(CursFisier::class, 'curs_id');
    // }

    public static function getQuery() {
        return 
            self::query()
            ->leftJoin(
                'categories',
                function($j) {
                    $j->on('categories.id', '=', 'cursuri.category_id');
                }
            )
        ;
    }

    public static function getItems($input) {
        return (new GetItems(
            $input, 
            self::getQuery()->with([
                'customercursuri.trimitere.detalii.customer', 
                'customercursuri.participanti'
            ]), 
            __CLASS__
        ))
        ->Perform();
    }

}