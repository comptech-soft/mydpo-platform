<?php

namespace MyDpo\Models\Customer\Accounts;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\Authentication\User;
use MyDpo\Models\Customer\Departments\Department;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Authentication\RoleUser;

use MyDpo\Performers\Customer\Account\GetUsers;
use MyDpo\Performers\Customer\Account\GetCustomers;

use MyDpo\Rules\Customer\Entities\Account\ValidAccountEmail;
use MyDpo\Events\Customer\Entities\Account\CreateAccountActivation;
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

    use Itemable, Actionable;
    
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
        'customer',
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
        return $this->belongsTo(Customer::class, 'customer_id')->select(['id', 'name', 'status', 'logo', 'email', 'city_id']);
    }

    public static function doInsert($input) {

        $user = User::create([
            ...$input['user'],
            'type' => 'b2b',
        ]);

        $input['user_id'] = $user->id;

        $account = self::create(collect($input)->except(['user'])->toArray());

        $role = RoleUser::CreateAccountRole($input['customer_id'], $user->id, $account->role_id);

        $customers = [
            $input['customer_id'] . '#' . $user->id,
        ];

        event(new CreateAccountActivation('account.activation', [...$input, 'customers' => $customers, 'account' => $account, 'role' => $role]));

        return self::where('id', $account->id)->first();
    }

    public static function doAttach($input) {

        $user = User::find($input['user_id']);

        $account = self::where('customer_id', $input['customer_id'])
            ->where('user_id', $input['user_id'])
            ->where('role_id', $input['role_id'])
            ->first();

        if(! $account)
        {
            $account = self::create(collect($input)->except(['user'])->toArray());
        }

        $role = RoleUser::CreateAccountRole($input['customer_id'], $user->id, $account->role_id);

        $customers = [
            $input['customer_id'] . '#' . $user->id,
        ];

        event(new CreateAccountActivation('account.activation', [...$input, 'customers' => $customers, 'account' => $account, 'role' => $role]));

        return self::where('id', $account->id)->first();
    }

    public static function doUpdate($input, $account) {

        $user = User::find($input['user_id']);

        $user->update($input['user']);

        $account->update(collect($input)->except(['user'])->toArray());

        return self::where('id', $account->id)->first();
    }

    public static function doActivation($input, $account) {

        $activation = Activation::byUserAndCustomer($input['user_id'], $input['customer_id'], $input['role_id']);

        RoleUser::CreateAccountRole($input['customer_id'], $input['user_id'], $input['role_id']);

        if($input['activated'] == 0)
        {
            // Contul trebuie deactivat
            $activation->update(['activated' => 0]);
            $account->update([
                'activated' => 0,
                'activated_at' => NULL,
                'role_id' => $input['role_id']
            ]);
        }
        else
        {
            // Contul trebuie activat
            $activation->update(['activated' => 1]);
            $account->update([
                'activated' => 1,
                'activated_at' => \Carbon\Carbon::now(),
                'role_id' => $input['role_id']
            ]);
        }
        
        return self::where('id', $account->id)->first();
    }

    /**
     * Resetarea parolei
     */
    /**
     * Resetarea parolei
     */
    public static function doPassword($input, $account) {
        
        $user = User::find($input['user_id']);

        $user->update([
            'password' => \Hash::make($input['user']['password'])
        ]);

        return self::where('id', $account->id)->first();
    }
    
    public static function doDelete($input, $account) {

        Activation::where('customer_id', $input['customer_id'])->where('user_id', $input['user_id'])->delete();

        RoleUser::where('customer_id', $input['customer_id'])->where('user_id', $input['user_id'])->delete();

        $account->delete();

        return self::where('id', $account->id)->first();
    }

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

    public static function GetRules($action, $input) {
       
        if($action == 'delete')
        {
            return NULL;
        }

        if($action == 'export')
        {
            return [
                'file' => 'required',
            ];
        }

        $result = [
            'customer_id' => 'required|exists:customers,id',
            'department_id' => 'required|exists:customers-departamente,id', 
            'role_id' => 'required|in:4,5', 
        ];

        if($action == 'insert')
        {
            $result['user.first_name'] = 'required';
            $result['user.last_name'] = 'required';
            
            $result['user.email'] = [
                'required',
                'email',
                new ValidAccountEmail($input)
            ];
        }

        if($action == 'attach')
        {
            $result['user_id'] = 'required|exists:users,id'; 
        }
    
        return $result;
    }

    /**
     * Din tabele customers-persons aflam utilizatorii
     * Utilizatorii se iau o singura data
     * Ne intereseaza si la cati clienti este 
     */
    public static function GetUsers($input) {
        return (new GetUsers($input))->Perform();
    }

    /**
     * Din tabele customers-persons aflam clientii
     * Clientii se iau o singura data
     * Ne intereseaza cate conturi are clientul???
     */
    public static function GetCustomers($input) {
        return (new GetCustomers($input))->Perform();
    }

    /**
     * 
     */
    public static function GetCustomersByUser($user) {
        return self::where('user_id', $user->id)
            ->leftJoin(
                'customers',
                function($q) {
                    $q->on('customers.id', '=', 'customers-persons.customer_id');
                }
            )
            ->whereRaw("((`customers`.`deleted` IS NULL) OR (`customers`.`deleted` = 0))")
            ->get();
    }

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
                'customers',
                function($q) {
                    $q->on('customers.id', '=', 'customers-persons.customer_id');
                }
            )
            ->leftJoin(
                'customers-departamente',
                function($q) {
                    $q->on('customers-departamente.id', '=', 'customers-persons.department_id');
                }
            )
            ->whereRaw("((`customers`.`deleted` IS NULL) OR (`customers`.`deleted` = 0))")
            ->select('customers-persons.*');
    }

    /**
     * Actualizeaza tabela customers-persons cu ce este in 
     * tabelele accounts-activations si roles-users
     */
    public static function SyncRecords($customer_id = NULL) {

        $accounts = (!! $customer_id ? self::where('customer_id', $customer_id)->get() : self::all());

        foreach($accounts as $i => $account)
        {
            if($account->role_id)
            {
                $activation = Activation::byUserAndCustomer($account->user_id, $account->customer_id, $account->role_id);
                $role_user = RoleUser::byUserAndCustomer($account->user_id, $account->customer_id, $account->role_id);
            }
        }
    }

    public static function SaveDashboardSettings($user_id, $customer_id, $items) {
        $account = self::where('customer_id', $customer_id)->where('user_id', $user_id)->first();

        $props = !! $account->props ? [...$account->props] : [];

        $account->props = [
            ...$props,
            'dashboard' => $items,
        ];

        $account->save();

        return $account->props;
    }
}