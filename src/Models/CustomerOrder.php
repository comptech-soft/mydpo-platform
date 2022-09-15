<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\Customer;
use MyDpo\Models\CustomerContract;
use MyDpo\Rules\CustomerOrder\OrderNumber;
use MyDpo\Traits\DaysDifference;

class CustomerOrder extends Model {
    
    use DaysDifference;

    protected $table = 'customers-orders';

    protected $casts = [
        'props' => 'json',
        'customer_id' => 'integer',
        'contract_id' => 'integer',
        'prelungire_automata' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'date_to_history' => 'json',
        'deleted' => 'integer',
    ];

    protected $fillable = [
        'id',
        'number',
        'date',
        'date_to',
        'customer_id',
        'contract_id',
        'prelungire_automata',
        'date_to_history',
        'deleted',
        'props',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'days_difference',
    ];

    /** order->services */
    // function services() {
    //     return $this->hasMany(CustomerOrderService::class, 'order_id');
    // }
    
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /** order->contract */
    public function contract() {
        return $this->belongsTo(CustomerContract::class, 'contract_id');
    }
    
    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function doAction($action, $input) {

        dd($input);
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function GetRules($action, $input) {
        if($action == 'delete')
        {
            return NULL;
        }
        $result = [
            'customer_id' => 'required|exists:customers,id',
            'contract_id' => 'required|exists:customers-contracts,id',
            'number' => [
                'required',
                'max:16',
                new OrderNumber($input),
            ],

            'date' => 'required|date',
            'date_to' => 'required|date',
        ];
        return $result;
    }
}