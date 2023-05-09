<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Helpers\Performers\Datatable\DoAction;

class CustomerCentralizator extends Model {

    use Itemable;

    protected $table = 'customers-centralizatoare';

    protected $casts = [
        'props' => 'json',
        'customer_id' => 'integer',
        'centralizator_id' => 'integer',
        'department_id' => 'integer',
        'visibility' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'centralizator_id',
        'department_id',
        'visibility',
        'number',
        'date',
        'responsabil_nume',
        'responsabil_functie',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // protected $with = [
    //     'centralizator',
    //     // 'cursusers',
    // ];

    // public function centralizator() {
    //     return $this->belongsTo(Curs::class, 'centralizator_id');
    // }

    // // public function cursusers() {
    // //     return $this->hasMany(CustomerCursUser::class, 'customer_curs_id');
    // // }

    // public static function getItems($input) {
    //     return (new GetItems(
    //         $input, 
    //         self::query()->has('centralizator'), 
    //         __CLASS__
    //     ))->Perform();
    // }

    // public static function getSummary($input) {
    //     return (new GetSummary($input))->Perform();
    // }
}