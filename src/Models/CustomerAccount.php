<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\User;
use MyDpo\Models\Customer;
use MyDpo\Models\CustomerDepartment;
use MyDpo\Models\RoleUser;
use MyDpo\Models\Activation;
use MyDpo\Rules\CustomerAccount\UniqueUser;
use MyDpo\Events\CustomerPersons\CustomerPersonCreateAccount;
use MyDpo\Performers\CustomerFolder\SaveFoldersAccess;
use MyDpo\Performers\CustomerAccount\UpdateRole;
use MyDpo\Performers\CustomerAccount\SaveDashboardPermissions;
use MyDpo\Scopes\NotdeletedScope;

class CustomerAccount extends Model {

    protected $table = 'customers-persons';

    protected $casts = [
        'props' => 'json',
        'permissions' => 'json',
        'id' => 'integer',
        'newsletter' => 'integer',
        'customer_id' => 'integer',
        'department_id' => 'integer',
        'user_id' => 'integer',
        'role_id' => 'integer',
        'activated' => 'integer',
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
        'role_id',
        'activated',
        'activated_at',
        'props',
        'permissions',
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

        $q = self::query()->leftJoin(
            'users',
            function($j) {
                $j->on('users.id', '=', 'customers-persons.user_id');
            }
        )
        ->leftJoin(
            'customers',
            function($j) {
                $j->on('customers.id', '=', 'customers-persons.customer_id');
            }
        )
        ->select('customers-persons.*');

        return (new GetItems($input, $q->with(['customer', 'department', 'user']), __CLASS__))->Perform();
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

        $accountInput = [
            'customer_id' => $input['customer_id'],
            'user_id' => $input['user_id'],
            'department_id' => $input['department_id'],
            'newsletter' => $input['newsletter'],
            'locale' => $input['locale'],
            'role_id' => $input['role_id'],
        ];

        $input['user']['password'] = $password = (\Str::random(10) . 'aA1!');
        $input['user']['password_confirmation'] = $password;
        $input['user']['type'] = 'b2b';

        $user = User::doAction('insert', $input['user']);

        $accountInput['user_id'] = $user['payload']['record']['id'];

        $account = self::create($accountInput);

        $roleUser = RoleUser::CreateAccountRole(
            $input['customer_id'], 
            $accountInput['user_id'], 
            $account->role_id,
        );

        event(new CustomerPersonCreateAccount([
            ...$input,
            'account' => $account,
            'roleUser' => $roleUser,
        ]));

        // if(array_key_exists('folders_access', $input))
        // {
        //     (new SaveFoldersAccess([
        //         'customer_id' => $input['customer_id'],
        //         'user_id' => $input['user_id'],
        //         'selectedFolders' => $input['folders_access'],
        //     ]))->Perform();
        // }

        // if(array_key_exists('dashboard_client', $input))
        // {
        //     $permissions = $account->permissions ?  $account->permissions : [];
        //     $account->permissions = [
        //         ...$permissions,
        //         'dashboard-client' => $input['dashboard_client']['dashboard-client'],
        //     ];
        //     $account->save();
        // }

        return $account;
    }

    public static function updateRole($action, $input) {
        return (new UpdateRole($input))->Perform();
    }

    public static function saveDashboardPermissions($input) {
        return (new SaveDashboardPermissions($input))->Perform();
    }

    public static function GetRules($action, $input) {
       
        if($action == 'delete')
        {
            return NULL;
        }

        $result = [
            'customer_id' => 'required|exists:customers,id',
            'department_id' => 'required|exists:customers-departamente,id', 
            'role_id' => 'required|in:4,5',
            'user.first_name' => 'required', 
            'user.last_name' => 'required',
            'user.email' => 'required|email',      
        ];

        // if( config('app.platform') == 'admin')
        // {
        //     $result['user_id'] = [
        //         'required',
        //         'exists:users,id',
        //         new UniqueUser($input),
        //     ];
        // }
    
        return $result;
    }

    public static function SyncRecords($customer_id = NULL) {

        if($customer_id)
        {
            $accounts = self::where('customer_id', $customer_id)->get();
        }
        else
        {
            $accounts = self::all();
        }

        foreach($accounts as $i => $account)
        {
            $activation = Activation::byUserAndCustomer($account->user_id, $customer_id);

            if($activation)
            {
                $account->activated = $activation->activated;
                $account->activated_at = $activation->activated_at;
            }

            $roleUser = RoleUser::byUserAndCustomer($account->user_id, $customer_id);

            if($roleUser)
            {
                $account->role_id = $roleUser->role_id;
            }

            $account->save();
        }
    }

}