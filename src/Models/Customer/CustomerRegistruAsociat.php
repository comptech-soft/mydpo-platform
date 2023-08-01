<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerRegistruAsociat extends Model {

    protected $table = 'customers-registers-asociate';

    protected $casts = [
        'props' => 'json',
        'register_id' => 'integer',
        'customer_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'register_id',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function UpdateOrCreateAsociere($input) {
        $record = self::where('customer_id', $input['customer_id'])
            ->where('register_id', $input['register_id'])
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