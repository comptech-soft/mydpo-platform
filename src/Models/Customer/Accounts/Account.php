<?php

namespace MyDpo\Models\Customer\Accounts;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Traits\Itemable;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\Authentication\User;
use MyDpo\Models\Customer\Departments\Department;
use MyDpo\Models\Customer\Customer_base as Customer;

// use MyDpo\Models\RoleUser;
// use MyDpo\Models\Activation;
// use MyDpo\Rules\CustomerAccount\ValidAccountEmail;
// use MyDpo\Events\CustomerPersons\CustomerPersonCreateAccount;
// use MyDpo\Performers\CustomerFolder\SaveFoldersAccess;
// use MyDpo\Performers\CustomerAccount\UpdateRole;
// use MyDpo\Performers\CustomerAccount\UpdateStatus;
// use MyDpo\Performers\CustomerAccount\SaveDashboardPermissions;
// use MyDpo\Performers\CustomerAccount\SaveFolderPermissions;
// use MyDpo\Performers\CustomerAccount\SavePermissions;
// use MyDpo\Performers\CustomerAccount\SaveFolderAccess;
// use MyDpo\Performers\CustomerAccount\AssignUser;

use MyDpo\Scopes\NotdeletedScope;

class Account extends Model {

    use Itemable;
    
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

    protected $with = [
        'user', 
        'department',
        'customer'
    ];

    protected $appends = [
        'role'
    ];

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

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id')->select(['id', 'name', 'status'])->with(['mystatus']);
    }

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }

    // public static function doUpdate($input, $account) {

    //     $account->update($input);

    //     if(array_key_exists('user', $input) && array_key_exists('role_id', $input['user']) )
    //     {
    //         $roleUser = RoleUser::CreateAccountRole(
    //             $input['customer_id'], 
    //             $input['user_id'], 
    //             $input['user']['role_id']
    //         );
    //     }

    //     return $account;
    // }

    // public static function doInsert($input) {

    //     $accountInput = [
    //         'customer_id' => $input['customer_id'],
    //         'user_id' => $input['user_id'],
    //         'department_id' => $input['department_id'],
    //         'newsletter' => $input['newsletter'],
    //         'locale' => $input['locale'],
    //         'role_id' => $input['role_id'],
    //     ];

    //     $input['user']['password'] = $password = (\Str::random(10) . 'aA1!');
    //     $input['user']['password_confirmation'] = $password;
    //     $input['user']['type'] = 'b2b';

    //     $user = User::doAction('insert', $input['user']);

    //     $accountInput['user_id'] = $user['payload']['record']['id'];

    //     $account = self::create($accountInput);

    //     $roleUser = RoleUser::CreateAccountRole(
    //         $input['customer_id'], 
    //         $accountInput['user_id'], 
    //         $account->role_id,
    //     );

    //     event(new CustomerPersonCreateAccount([
    //         ...$input,
    //         'account' => $account,
    //         'roleUser' => $roleUser,
    //     ]));

    //     return $account;
    // }

    // public static function updateRole($action, $input) {
    //     return (new UpdateRole($input))->Perform();
    // }

    // public static function updateStatus($action, $input) {
    //     return (new UpdateStatus($input))->Perform();
    // }

    // public static function saveDashboardPermissions($input) {
    //     return (new SaveDashboardPermissions($input))->Perform();
    // }

    // public static function saveFolderPermissions($input) {
    //     return (new SaveFolderPermissions($input))->Perform();
    // }

    // public static function savePermissions($input) {
    //     return (new SavePermissions($input))->Perform();
    // }

    // public static function saveFolderAccess($input) {
    //     return (new SaveFolderAccess($input))->Perform();
    // }

    // public static function assignUser($input) {
    //     return (new AssignUser($input))->Perform();
    // }

    // public static function GetRules($action, $input) {
       
    //     if($action == 'delete')
    //     {
    //         return NULL;
    //     }

    //     $result = [
    //         'customer_id' => 'required|exists:customers,id',
    //         'department_id' => 'required|exists:customers-departamente,id', 
    //         'role_id' => 'required|in:4,5', 
    //     ];

    //     if($action == 'insert')
    //     {
    //         $result['user.first_name'] = 'required';
    //         $result['user.last_name'] = 'required';
            
    //         $result['user.email'] = [
    //             'required',
    //             'email',
    //             new ValidAccountEmail($input)
    //         ];
    //     }
    
    //     return $result;
    // }

    // public static function SyncRecords($customer_id = NULL) {

    //     if($customer_id)
    //     {
    //         $accounts = self::where('customer_id', $customer_id)->get();
    //     }
    //     else
    //     {
    //         $accounts = self::all();
    //     }


    //     foreach($accounts as $i => $account)
    //     {
    //         $activation = Activation::byUserAndCustomer($account->user_id, $account->customer_id);
            
    //         if(!! $activation)
    //         {
    //             $account->activated = $activation->activated;
    //             $account->activated_at = $activation->activated_at;
    //         }

    //         $roleUser = RoleUser::byUserAndCustomer($account->user_id, $account->customer_id);

    //         if(!! $roleUser)
    //         {
    //             $account->role_id = $roleUser->role_id;
    //         }

    //         $account->save();
    //     }
    // }

    public static function GetQuery() {
        return 
            self::query()
            ->leftJoin(
                'users',
                function($q) {
                    $q->on('users.id', '=', 'customers-persons.user_id');
                }
            )
            ->leftJoin(
                'customers-departamente',
                function($q) {
                    $q->on('customers-departamente.id', '=', 'customers-persons.department_id');
                }
            )
            ->select('customers-persons.*');
    }
}