<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\Service;
use MyDpo\Models\CustomerOrder;
use MyDpo\Rules\CustomerService\UniqueOrderService;

class CustomerService extends Model {

    protected $table = 'customers-orders-services';

    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'contract_id' => 'integer',
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
        'contract_id',
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

    public function service() {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function order() {
        return $this->belongsTo(CustomerOrder::class, 'order_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query()->with(['service.type']), __CLASS__))->Perform();
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function doInsert($input, $service) {
        $service = self::create($input);
        return $service;
    }

    public static function GetRules($action, $input) {

        if($action == 'delete')
        {
            return NULL;
        }

        $result = [
            'customer_id' => 'required|exists:customers,id',
            'contract_id' => 'required|exists:customers-contracts,id',
            'order_id' => 'required|exists:customers-orders,id',
            'service_id' => [
                'required',
                'exists:services,id',
                new UniqueOrderService($input),
            ],
        ];
        return $result;
    }

    public static function syncWithOrders() {
        $records = self::with(['order.cntract'])->get();

        foreach($records as $i => $record)
        {
            dd($record);
        }
    }

}