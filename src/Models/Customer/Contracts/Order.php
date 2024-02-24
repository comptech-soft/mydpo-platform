<?php

namespace MyDpo\Models\Customer\Contracts;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
// use MyDpo\Models\Customer\Customer_base as Customer;
// use MyDpo\Models\Contract;
// use MyDpo\Models\CustomerService;
use MyDpo\Rules\CustomerOrder\OrderNumber;
use MyDpo\Traits\DaysDifference;
use MyDpo\Traits\Itemable;

class Order extends Model {
    
    use Itemable, DaysDifference;

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

    protected $with = [
        'services.service',
    ];

    function services() {
        return $this->hasMany(OrderService::class, 'order_id');
    }
    
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /** order->contract */
    public function contract() {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
    
    public static function getItems($input) {
        return (new GetItems($input, self::query()->with(['Contract', 'Customer']), __CLASS__))->Perform();
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public function attachService($service) {
        CustomerService::doAction('insert', [
            'id' => NULL,
            'service_id' => $service['service_id'],
            ...$service['record'],
            'customer_id' => $this->contract->customer_id,
            'contract_id' => $this->contract_id,
            'order_id' => $this->id,
        ]);
    }

    public static function doInsert($input, $order) {
        
        $order = self::create([
            ...$input,
            'date_to_history' => [
                [
                    'date_to' =>  $input['date_to'],
                    'updated_by' => \Auth::user()->id,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d'),
                ]
            ]
        ]);

        if( array_key_exists('services', $input) )
        {
            foreach($input['services'] as $i => $service)
            {
                $order->attachService($service);
            }
        }

        return $order;
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