<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerCentralizatorAsociat extends Model {

    protected $table = 'customers-centralizatoare-asociere';

    protected $casts = [
        'props' => 'json',
        'centralizator_id' => 'integer',
        'customer_id' => 'integer',
        'is_associated' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'centralizator_id',
        'is_associated',
        'props',
        'created_by',
        'updated_by',
    ];

    public static function UpdateOrCreateAsociere($input) {

        $record = self::where('customer_id', $input['customer_id'])
            ->where('centralizator_id', $input['centralizator_id'])
            ->first();
        
        if( ! $record )
        {
            $record = self::create($input);
        }
        else
        {
            $record->update($input);
        }
    }

}