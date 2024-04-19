<?php

namespace MyDpo\Models\Customer\Accounts;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Exportable;
use MyDpo\Traits\Importable;
use MyDpo\Models\Authentication\User;
use MyDpo\Models\Authentication\RoleUser;
use MyDpo\Models\Authentication\Role;
use MyDpo\Models\Customer\Departments\Department;
use MyDpo\Models\Customer\Customer;
use MyDpo\Exports\Customer\Entities\Account\Exporter;
use MyDpo\Imports\Customer\Entities\Account\Importer;
use MyDpo\Performers\Customer\Account\GetUsers;
use MyDpo\Performers\Customer\Account\GetCustomers;
use MyDpo\Rules\Customer\Entities\Account\ValidAccountEmail;
use MyDpo\Events\Customer\Entities\Account\CreateAccountActivation;
use MyDpo\Scopes\NotdeletedScope;

class Account extends Model {

    use Itemable, Actionable, Exportable, Importable;
    
    protected $table = 'customers-persons';

    protected $casts = [
        'props' => 'json',
        'permissions' => 'json',
        'dashboard_items_visibility' => 'json',
        'menus_items_visibility' => 'json',
        'actions_items_visibility' => 'json',
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
        'dashboard_items_visibility',
        'menus_items_visibility',
        'actions_items_visibility',
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

    protected static function GetExporter($input) {
        return new Exporter($input); 
    }

    protected static function GetImporter($input) {
        return new Importer($input); 
    }

    /**
     * Crearea unui cont client
     */
    public static function doInsert($input) {
        /**
         * #1. Se creaza inregistrarea in tabela [users]
         * se ataseaza id-ul userului la $input
         */
        $user = User::create([...$input['user'], 'type' => 'b2b']);
        $input['user_id'] = $user->id;

        /** 
         * #2. Se creaza inregistrarea in [customers-persons] 
         * se preiau setarile default (de la rolul master sau user) pentru dashboard items visibility si se salveaza
         **/
        $account = self::create(collect($input)->except(['user'])->toArray());
        $account->setDefaultDashboardItemsVisibility();

        /** 
         * #3. Se ataseaza rolul in tabela [role-users] 
         **/
        $role = RoleUser::CreateAccountRole($input['customer_id'], $user->id, $account->role_id);

        /** 
         * #4. Se genereaza evenimentul CreateAccountActivation 
         * cu template-ul de email account.activation 
         * ATENTIE! Emailul se trimite cu php artisan emails:send
         * Acesta trebuie sa fie in jobs
         **/
        event(new CreateAccountActivation('account.activation', [
            ...$input, 
            'customers' => [
                $input['customer_id'] . '#' . $user->id,
            ], 
            'account' => $account, 
            'role' => $role
        ]));

        return self::where('id', $account->id)->first();
    }

    /**
     * se preiau setarile default (de la rolul master sau user) pentru dashboard items visibility si se salveaza
     */
    public function setDefaultDashboardItemsVisibility() {

        $role = Role::find($this->role_id);

        $dashboard = array_key_exists('dashboard', $role->permissions) ? $role->permissions['dashboard'] : NULL; 

        $this->dashboard_items_visibility = $dashboard;
        $this->save();
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
            $account->setDefaultDashboardItemsVisibility();
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
        
        RoleUser::CreateAccountRole($input['customer_id'], $user->id, $account->role_id);

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
            $activation->update([
                'activated' => 1,
                'activated_at' => ($d = \Carbon\Carbon::now()),
            ]);
            $account->update([
                'activated' => 1,
                'activated_at' => $d,
                'role_id' => $input['role_id']
            ]);
        }
        
        return self::where('id', $account->id)->first();
    }

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

    public static function doDashoarditems($input, $account) {
        $account->dashboard_items_visibility = $input['dashboard_items_visibility'];
        $account->save();
        return self::where('id', $account->id)->first();
    }

    public static function doMenuitems($input, $account) {
        $account->menus_items_visibility = $input['menus_items_visibility'];
        $account->save();
        return self::where('id', $account->id)->first();
    }

    public static function doActionsitems($input, $account) {
        $actions_items_visibility = !! $account->actions_items_visibility ? $account->actions_items_visibility : [];
        $actions_items_visibility[$input['active_node']['id']] = $input['active_node'];
        $account->actions_items_visibility = $actions_items_visibility;
        $account->save();
        return self::where('id', $account->id)->first();
    }

    public static function GetRules($action, $input) {
       
        if( in_array($action, ['delete', 'dashoarditems', 'menuitems', 'actionsitems']) )
        {
            return NULL;
        }

        if($action == 'export')
        {
            return [
                'file_name' => 'required',
            ];
        }

        if($action == 'import')
        {
            return [
                'file' => 'required',
                'customer_id' => 'required|exists:customers,id',
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

        /**
         * Atentie! S-ar putea ca si userii sa fie deleted
         */
        
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

                if(!! $activation)
                {
                    $account->activated = (!! $activation->activated ? 1 : 0);
                    $account->activated_at = $activation->activated_at;
                    $account->save();
                }
            }
        }
    }

    /**
     * ATENTIE.
     * Avem in doua locuri: props['dashboard'] si in dashboard_items_visibility
     */
    public static function SaveDashboardSettings($user_id, $customer_id, $items) {
        $account = self::where('customer_id', $customer_id)->where('user_id', $user_id)->first();

        $props = !! $account->props ? [...$account->props] : [];

        $account->props = [
            ...$props,
            'dashboard' => $items,
        ];

        $account->dashboard_items_visibility = $items;

        $account->save();

        return $account->props;
    }
}