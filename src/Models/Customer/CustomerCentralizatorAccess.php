<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;

class CustomerCentralizatorAccess extends Model {

    use Itemable;

    protected $table = 'customers-centralizatoare-access';

    protected $casts = [
        'props' => 'json',
        'departamente' => 'json',
        'centralizator_id' => 'integer',
        'customer_id' => 'integer',
        'customer_centralizator_id' => 'integer',
        'user_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_centralizator_id',
        'customer_id',
        'centralizator_id',
        'user_id',
        'departamente',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

}