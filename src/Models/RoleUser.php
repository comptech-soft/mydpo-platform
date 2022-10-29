<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Role;
use MyDpo\Models\Customer;
use MyDpo\Helpers\Performers\Datatable\GetItems;

class RoleUser extends Model {

    protected $table = 'role_users';

    protected $casts = [
        'user_id' => 'integer',
        'role_id' => 'integer',
        'user_id' => 'integer',
        'customer_id' => 'integer',
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

    public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query()->with(['role', 'customer']), __CLASS__))->Perform();
    }

    public static function CreateAccountRole($customer_id, $user_id, $role_id) {

        if(!! $customer_id && in_array($role_id, [4, 5])  )
        {
            self::where('customer_id', $customer_id)->whereIn('role_id',[4, 5])->where('user_id', $user_id)->delete();
        }

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

    public static function byUserAndCustomer($user_id, $customer_id) {
        return self::where('user_id', $user_id)
            ->where('customer_id', $customer_id)
            ->first();
    }

}