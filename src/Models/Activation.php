<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\Customer;
// use MyDpo\Rules\Category\UniqueName;

class Activation extends Model {
   
    protected $table = 'accounts-activations';

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'customer_id' => 'integer',
        'role_id' => 'integer',
        'activated' => 'integer',
    ];

    protected $fillable = [
        'id',
        'user_id',
        'customer_id',
        'role_id',
        'token',
        'activated',
        'activated_at',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public static function byToken($token) {
        return self::where('token', $token)->where('activated', 0)->first();
    }

    public static function byUserAndCustomer($user_id, $customer_id) {
        return self::where('user_id', $user_id)
            ->where('customer_id', $customer_id)
            ->first();
    }

    public static function createActivation($user_id, $customer_id, $role_id) {

        $record = self::where('user_id', $user_id)
            ->where('customer_id', $customer_id)
            ->first();

        if(!! $record)
        {
            $record->update([
                'activated' => 0,
                'activated_at' => NULL,
                'role_id' => $role_id,
            ]);
        }
        else
        {
            $record = self::create([
                'user_id' => $user_id,
                'customer_id' => $customer_id,
                'role_id' => $role_id,
                'token' => \Str::random(64),
            ]);   
        }

        return $record;
    }



}