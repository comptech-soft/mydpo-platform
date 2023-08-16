<?php

namespace MyDpo\Models\Customer\Teams;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Authentication\User;
// use MyDpo\Performers\UserCustomer\UpdateUserCustomers;
use MyDpo\Traits\Itemable;

class Team extends Model {

    use Itemable;

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
        'user'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query()->with(['user', 'customer']), __CLASS__))->Perform();
    // }

    // public static function updateUserCustomers($input) {
    //     return (new UpdateUserCustomers($input))
    //         ->SetSuccessMessage('Setare cu succes')
    //         ->Perform();
    // }

    public static function GetQuery() {
        return 
            self::query()
            ->leftJoin(
                'users',
                function($q) {
                    $q->on('users.id', '=', 'users-customers.user_id');
                }
            )
            ->whereHas('user')
            ->select('users-customers.*');;
    }

}