<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
// use MyDpo\Models\Curs;
// use MyDpo\Rules\Category\UniqueName;

class Activation extends Model {
   
    protected $table = 'accounts-activations';

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'customer_id' => 'integer',
        'role_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'user_id',
        'customer_id',
        'role_id',
        'token',
    ];

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
    }



}