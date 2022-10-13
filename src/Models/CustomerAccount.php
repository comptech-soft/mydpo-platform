<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\User;
use MyDpo\Models\Customer;
use MyDpo\Models\CustomerDepartment;
use MyDpo\Models\RoleUser;
use MyDpo\Rules\CustomerAccount\UniqueUser;
use MyDpo\Events\CustomerPersons\CustomerPersonCreateAccount;
use MyDpo\Scopes\NotdeletedScope;

class CustomerAccount extends Model {

    protected $table = 'customers-persons';

    protected $casts = [
        'props' => 'json',
        'id' => 'integer',
        'newsletter' => 'integer',
        'customer_id' => 'integer',
        'department_id' => 'integer',
        'user_id' => 'integer',
        'order_no' => 'integer',
        'updated_by' => 'integer',
        'created_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'phone',
        'department_id',
        'newsletter',
        'locale',
        'customer_id',
        'user_id',
        'props',
        'order_no',
        'created_by',
        'updated_by',
        'deleted',
        'deleted_by',
    ];

    protected static function booted() {
        static::addGlobalScope( new NotdeletedScope() );
    }

    protected $with = ['user', 'department'];

    protected $appends = ['role'];

    public function getRoleAttribute() {
        $r = NULL;
        foreach($this->user->roles as $i => $role)
        {
            if( ($role->type == 'b2b') && ($role->pivot->customer_id == $this->customer_id) )
            {
                $r = $role;
            }
        }
        return $r;
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function department() {
        return $this->belongsTo(CustomerDepartment::class, 'department_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query()->with(['customer', 'department', 'user']), __CLASS__))->Perform();
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function doUpdate($input, $account) {

        $account->update($input);

        $roleUser = RoleUser::CreateAccountRole(
            $input['customer_id'], 
            $input['user_id'], 
            $input['user']['role_id']
        );

        return $account;
    }

    public static function doInsert($input) {

        $account = self::create([
            'customer_id' => $input['customer_id'],
            'user_id' => $input['user_id'],
            'department_id' => $input['department_id'],
            'newsletter' => $input['newsletter'],
            'locale' => $input['locale'],
        ]);

        $roleUser = RoleUser::CreateAccountRole(
            $input['customer_id'], 
            $input['user_id'], 
            $input['user']['role_id']
        );

        event(new CustomerPersonCreateAccount([
            ...$input,
            'account' => $account,
            'roleUser' => $roleUser,
        ]));

        return $account;

    } 

    public static function GetRules($action, $input) {
       
        if($action == 'delete')
        {
            return NULL;
        }

        $result = [
            'customer_id' => 'required|exists:customers,id',
            'department_id' => 'required|exists:customers-departamente,id', 
            'user_id' => [
                'required',
                'exists:users,id',
                new UniqueUser($input),
            ],
            'user.role_id' => 'required|in:4,5'         
        ];

        return $result;
    }

    

}