<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\Customer;
use MyDpo\Rules\CustomerContract\ContractNumber;
use MyDpo\Traits\DaysDifference;

class Contract extends Model {

    use DaysDifference;
    
    protected $table = 'customers-contracts';

    protected $casts = [
        'props' => 'json',
        'prelungire_automata' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'customer_id' => 'integer',
        'date_to_history' => 'json',
    ];

    protected $fillable = [
        'id',
        'number',
        'date_from',
        'date_to',
        'customer_id',
        'prelungire_automata',
        'date_to_history',
        'deleted',
        'status',
        'props',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = [
        'days_difference',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public function attachOrder($order) {
        return CustomerOrder::doAction('insert', [
            'id' => NULL,
            ...$order,
            'contract_id' => $this->id,
        ]);
    }

    public static function doInsert($input, $contract) {
        
        $contract = self::create([
            ...$input,
            'date_to_history' => [
                [
                    'date_to' =>  $input['date_to'],
                    'updated_by' => \Auth::user()->id,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d'),
                ]
            ]
        ]);

        if( array_key_exists('orders', $input) )
        {
            foreach($input['orders'] as $i => $order)
            {
                $result = $contract->attachOrder($order);
                if( ! $result['success'] )
                {
                    throw new \Exception($result['message']);
                }
            }
        }

        return $contract;
    }


    public static function GetRules($action, $input) {
       
        if($action == 'delete')
        {
            return NULL;
        }

        $result = [
            'customer_id' => 'required|exists:customers,id',
            'number' => [
                'required',
                'max:16',
                new ContractNumber($input),
            ],
            'status' => 'required',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ];

        
        return $result;
    }


}