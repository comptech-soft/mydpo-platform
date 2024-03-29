<?php

namespace MyDpo\Models\Customer\Contracts;

use Illuminate\Database\Eloquent\Model;

class OrderService extends Model {

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
        return $this->belongsTo(\MyDpo\Models\Livrabile\Contracts\Service::class, 'service_id');
    }
                                 
    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // public function customer() {
    //     return $this->belongsTo(Customer::class, 'customer_id');
    // }

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query()->with(['customer', 'order.contract', 'service.type']), __CLASS__))->Perform();
    // }

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }

    // public static function doInsert($input, $service) {
    //     $service = self::create($input);
    //     return $service;
    // }

    // public static function GetRules($action, $input) {

    //     if($action == 'delete')
    //     {
    //         return NULL;
    //     }

    //     $result = [
    //         'customer_id' => 'required|exists:customers,id',
    //         'contract_id' => 'required|exists:customers-contracts,id',
    //         'order_id' => 'required|exists:customers-orders,id',
    //         'service_id' => [
    //             'required',
    //             'exists:services,id',
    //             new UniqueOrderService($input),
    //         ],
    //     ];
    //     return $result;
    // }

    public static function syncWithOrders() {
        $records = self::with(['order'])->get();

        foreach($records as $i => $record)
        {
            $record->customer_id = $record->order->customer_id;
            $record->contract_id = $record->order->contract_id;
            $record->save();
            
        }
    }

}