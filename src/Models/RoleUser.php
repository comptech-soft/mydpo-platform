<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model {

    protected $table = 'role_users';

    protected $casts = [
        'user_id' => 'integer',
        'role_id' => 'integer',
        'user_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'role_id',
        'customer_id',
        'created_by',
        'updated_by'
    ];

    public static function CreateAccountRole($customer_id, $user_id, $role_id) {

        $record = self::where('customer_id', $customer_id)
            ->where('user_id', $user_id)
            ->where('role_id', $role_id)
            ->first();

        if( $record )
        {
            return $record;
        }

        return self::create([
            'user_id' => $user_id,
            'role_id' => $role_id,
            'customer_id' => $customer_id,
            'created_by' => \Auth::user()->id,
        ]);

    }

}