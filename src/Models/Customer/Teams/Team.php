<?php

namespace MyDpo\Models\Customer\Teams;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Authentication\User;
// use MyDpo\Performers\UserCustomer\UpdateUserCustomers;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class Team extends Model {

    use Itemable, Actionable;

    protected $table = 'users-customers';

    protected $casts = [
        'props' => 'json',
        'user_id' => 'integer',
        'deleted' => 'integer',
        'customer_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'user_id',
        'customer_id',
        'phone',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $with = [
        'user',
        'customer',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public static function updateUserCustomers($input) {
    //     return (new UpdateUserCustomers($input))
    //         ->SetSuccessMessage('Setare cu succes')
    //         ->Perform();
    // }

    public static function doAttach($input, $record) {
        
        $record = self::where('user_id', $input['user_id'])->where('customer_id', $input['customer_id'])->first();

        if(! $record )
        {
            $record = self::create([
                'user_id' => $input['user_id'],
                'customer_id' => $input['customer_id'],
            ]);
        }

        return $record;
    }

    public static function GetQuery() {
        return 
            self::query()
            ->leftJoin(
                'users',
                function($q) {
                    $q->on('users.id', '=', 'users-customers.user_id');
                }
            )
            ->leftJoin(
                'customers',
                function($q) {
                    $q->on('customers.id', '=', 'users-customers.customer_id');
                }
            )
            ->whereHas('user')
            ->select('users-customers.*');
    }

    public static function GetRules($action, $input) {

        if($action == 'delete')
        {
            return NULL;
        }

        if($action == 'attach')
        {
            return [
                'customer_id' => 'required|exists:customers,id',
            ];
        }

        if( $action == 'update')
        {
            return [
                'customer_id' => 'required|exists:customers,id',
            ];
        }
        return NULL;
    }

}