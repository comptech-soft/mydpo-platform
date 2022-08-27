<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Customer;

class CustomerContract extends Model {

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
        'deleted',
        'status',
        'props',
        'created_by',
        'updated_by',
        'deleted_by'
    ];


    // function orders() {
    //     return $this->hasMany(CustomerOrder::class, 'contract_id')->orderBy('date', 'desc');
    // }
    
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}