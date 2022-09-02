<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;

class CustomerService extends Model {

    protected $table = 'customers-orders-services';

    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'comanda_id' => 'integer',
        'order_id' => 'integer',
        'service_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',

        'tarif' => 'decimal:2',
        'tarif_ore_suplimentare' => 'decimal:2',
        'ore_incluse_abonament' => 'integer',

        'deleted' => 'integer',

        'props' => 'json',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'comanda_id',
        'order_id',
        'service_id',
        'tarif',
        'tarif_ore_suplimentare',
        'tip_abonament',
        'ore_incluse_abonament',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }
}