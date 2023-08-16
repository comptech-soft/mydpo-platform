<?php

namespace MyDpo\Models\Customer\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Chestionar;
// use MyDpo\Models\CustomerChestionarUser;
use MyDpo\Performers\CustomerChestionar\GetSummary;

class CustomerChestionar extends Model {

    protected $table = 'customers-chestionare';

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
        'chestionar',
        // 'cursusers',
    ];

    public function chestionar() {
        return $this->belongsTo(Curs::class, 'chestionar_id');
    }

    // public function cursusers() {
    //     return $this->hasMany(CustomerCursUser::class, 'customer_curs_id');
    // }

    public static function getItems($input) {
        return (new GetItems(
            $input, 
            self::query()->has('chestionar'), 
            __CLASS__
        ))->Perform();
    }

    public static function getSummary($input) {
        return (new GetSummary($input))->Perform();
    }
}