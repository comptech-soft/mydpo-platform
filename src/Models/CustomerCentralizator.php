<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Centralizator;
// use MyDpo\Models\CustomerCentralizatorUser;
use MyDpo\Performers\CustomerCentralizator\GetSummary;

class CustomerCentralizator extends Model {

    protected $table = 'customers-centralizatoare';

    protected $casts = [
        // 'props' => 'json',
        // 'customer_id' => 'integer',
        // 'curs_id' => 'integer',
        // 'trimitere_id' => 'integer',
        // 'created_by' => 'integer',
        // 'updated_by' => 'integer',
        // 'deleted_by' => 'integer',
        // 'deleted' => 'integer',
        // 'effective_time' => 'float',
        // 'assigned_users' => 'json',
    ];

    protected $fillable = [
        // 'id',
        // 'customer_id',
        // 'curs_id',
        // 'trimitere_id',
        // 'status',
        // 'effective_time',
        // 'assigned_users',
        // 'props',
        // 'deleted',
        // 'created_by',
        // 'updated_by',
        // 'deleted_by'
    ];

    protected $with = [
        'centralizator',
        // 'cursusers',
    ];

    public function centralizator() {
        return $this->belongsTo(Curs::class, 'centralizator_id');
    }

    // public function cursusers() {
    //     return $this->hasMany(CustomerCursUser::class, 'customer_curs_id');
    // }

    public static function getItems($input) {
        return (new GetItems(
            $input, 
            self::query()->has('centralizator'), 
            __CLASS__
        ))->Perform();
    }

    public static function getSummary($input) {
        return (new GetSummary($input))->Perform();
    }
}