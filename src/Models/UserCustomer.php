<?php

namespace MyDpo\Models;

use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Customer;
use MyDpo\Models\User;

class UserCustomer extends Model {

    protected $table = 'users-customers';

    protected $casts = [
        'props' => 'json',
        'user_id' => 'integer',
        'deleted' => 'integer',
        'customer_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'user_id',
        'customer_id',
        'phone',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

}