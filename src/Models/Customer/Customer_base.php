<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\Customer\Statuses\Status;
use MyDpo\Models\System\City;
use MyDpo\Models\Customer\Contracts\CustomerContract;
use MyDpo\Models\UserCustomer;
use MyDpo\Performers\Customer\GetCustomersByIds;
use MyDpo\Models\Customer\Dashboard\Item;
use MyDpo\Models\Customer\Documents\Folder;

use MyDpo\Traits\Itemable;

class Customer_base extends Model {

    use Itemable;
    
    protected $table = 'customers';

    protected $casts = [
        'logo' => 'json',
        'props' => 'json',
        'city_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
		'default_folders_created' => 'integer',
        'has_contract' => 'integer',
        'has_order' => 'integer',
    ];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'email',
        'phone',
        'street',
        'street_number',
        'postal_code',
        'address',
        'vat',
        'props',
        'newsletter',
        'locale',
        'status',
        'details',
        'city_id',
        'logo',
		'default_folders_created',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by',
        'country',
        'region',
        'city_name',
        'has_contract',
        'has_order',
    ];

    public function getMylogoAttribute() {
        
        if( ! $this->logo )
        {
            return NULL;
        }

        return array_key_exists('url', $this->logo) ? $this->logo['url'] : NULL;
    }

    public function getFullCityAttribute() {
        
        if( ! $this->city_id )
        {
            return NULL;
        }

        $r = [
            $this->city->name,
            $this->city->region->name,
            $this->city->region->country->name,
        ];

        return  implode(', ', $r);
    }

    public function getLastContractAttribute() {
        
        return $this->contracts->first();
    }

    public function getRegionIdAttribute() {
        if( ! $this->city_id )
        {
            return NULL;
        }
        return $this->city->region->id;
    }

    public function getCountryIdAttribute() {
        if( ! $this->city_id )
        {
            return NULL;
        }
        return $this->city->region->country->id;
    }

    public function mystatus() {
        return $this->belongsTo(Status::class, 'status', 'slug');
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id');
    }

    function contracts() {
        return $this->hasMany(CustomerContract::class, 'customer_id')->orderBy('date_to', 'desc');
    }

    function accounts() {
        return $this->hasMany(CustomerAccount::class, 'customer_id');
    }

    function team() {
        return $this->hasMany(UserCustomer::class, 'customer_id');
    }
   
    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function getItemsWithPersons($input) {
        return (new GetItems($input, self::query()->whereHas('accounts')->with(['accounts']), __CLASS__))->Perform();
    }

    public static function getCustomersByIds($input) {
        return (new GetCustomersByIds($input))->Perform();
    }
    
    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function doDelete($action, $record) {
        $record->deleted = 1;
        $record->name = '#' . $record->id . '#' . $record->name;
        $record->status = 'inactive';
        $record->save();
        return $record;
    }

    public static function AfterAction($action, $input, $payload) {
        $payload['record']->SyncronizeField();
        if($action == 'insert')
        {
            $payload['record']->SetDefaultDashboardPermissions();

            /**
             * La crearea customer sa se creeze automat folderele default
             */
            Folder::CreateDefaultFolders($payload['record']->id);
        }
    }
    
    public static function GetRules($action, $input) {

        if($action == 'delete')
        {
            return NULL;
        }

        $result = [
            'name' => 'required|max:191|unique:customers,name',
            'email' => 'required|email',
            'city_id' => 'required|exists:cities,id',
        ];

        if( $action == 'update')
        {
            $result['name'] .= (',' . $input['id']);
        }
        return $result;
    }

    public function GetMyOperators($all) {

        if($all)
        {
            $sql = "
                SELECT
                    `users-customers`.user_id
                FROM `users-customers`
                    LEFT JOIN `role_users`
                        LEFT JOIN `roles`
                        ON `roles`.id = `role_users`.role_id
                    ON `role_users`.user_id = `users-customers`.user_id
                WHERE 
                    (`users-customers`.customer_id = " . $this->id . ")
                    AND (`roles`.slug = 'operator')
                ";
        }
        else
        {
            $sql = "
                SELECT
                    `users-customers`.user_id
                FROM `users-customers`
                    LEFT JOIN `role_users`
                        LEFT JOIN `roles`
                        ON `roles`.id = `role_users`.role_id
                    ON `role_users`.user_id = `users-customers`.user_id
                WHERE 
                    (`users-customers`.customer_id = " . $this->id . ")
                    AND (`roles`.slug = 'operator')
                    AND (`users-customers`.user_id <> " . \Auth::user()->id . ")
                ";
        }

        return \DB::select($sql);
    }

    public function GetMyMasters($all) {

        if($all)
        {
            $sql = "
                SELECT
                    `customers-persons`.user_id
                FROM `customers-persons`
                LEFT JOIN `role_users`
                    LEFT JOIN `roles`
                    ON `roles`.id = `role_users`.role_id
                ON `role_users`.user_id = `customers-persons`.user_id
                WHERE 
                    (`customers-persons`.customer_id = " . $this->id . ")
                    AND (`role_users`.customer_id = " . $this->id . ")
                    AND (`roles`.slug = 'master')
            ";
        }
        else
        {
            $sql = "
                SELECT
                    `customers-persons`.user_id
                FROM `customers-persons`
                LEFT JOIN `role_users`
                    LEFT JOIN `roles`
                    ON `roles`.id = `role_users`.role_id
                ON `role_users`.user_id = `customers-persons`.user_id
                WHERE 
                    (`customers-persons`.customer_id = " . $this->id . ")
                    AND (`role_users`.customer_id = " . $this->id . ")
                    AND (`roles`.slug = 'master')
                    AND (`customers-persons`.user_id <> " . \Auth::user()->id . ")
            ";
        }

        return \DB::select($sql);
    }

    public function GetMyUserByFolderAccess($folder_id, $all) {

        if($all)
        {
            $sql = "
                SELECT
                    `customers-persons`.user_id
                    FROM `customers-persons`
                    LEFT JOIN `role_users`
                        LEFT JOIN `roles`
                        ON `roles`.id = `role_users`.role_id
                    ON `role_users`.user_id = `customers-persons`.user_id
                    LEFT JOIN `customers-folders-permissions`
                    ON `customers-folders-permissions`.user_id = `customers-persons`.user_id
                    WHERE 
                        (`customers-persons`.customer_id = " . $this->id . ")
                        AND (`role_users`.customer_id = " . $this->id . ")
                        AND (`customers-folders-permissions`.folder_id = " . $folder_id . ")
                        AND (`customers-folders-permissions`.has_access = 1)
                        AND (`roles`.slug = 'customer')
            ";
        }
        else
        {
            $sql = "
                SELECT
                    `customers-persons`.user_id
                    FROM `customers-persons`
                    LEFT JOIN `role_users`
                        LEFT JOIN `roles`
                        ON `roles`.id = `role_users`.role_id
                    ON `role_users`.user_id = `customers-persons`.user_id
                    LEFT JOIN `customers-folders-permissions`
                    ON `customers-folders-permissions`.user_id = `customers-persons`.user_id
                    WHERE 
                        (`customers-persons`.customer_id = " . $this->id . ")
                        AND (`role_users`.customer_id = " . $this->id . ")
                        AND (`customers-folders-permissions`.folder_id = " . $folder_id . ")
                        AND (`customers-folders-permissions`.has_access = 1)
                        AND (`roles`.slug = 'customer')
                        AND (`customers-persons`.user_id <> " . \Auth::user()->id . ")

            ";
        }

        return \DB::select($sql);
    }

    public function SyncronizeField() {
        $this->city_name = $this->city 
            ? $this->city->name 
            : NULL;
            
        $this->region = $this->city && $this->city->region 
            ? $this->city->region->name 
            : NULL;
            
        $this->country = $this->city && $this->city->region && $this->city->region->country 
            ? $this->city->region->country->name 
            : NULL;           
            
        $this->has_contract = (!! $this->contracts->count() ? 1 : 0);    
            
        if($this->has_contract)
        {
            $days_difference = $this->contracts->first()->days_difference;
            $this->contract_expirat = ($days_difference['days'] > 0 ? 1 : 0);
        }

        $this->save();
    }

    public function SetDefaultDashboardPermissions() {
 
        $items = Item::all();

        $props['dashboard'] = $items->map( function($item) {

            return [
                'id' => $item->id,
                'name' => $item->name,
                'visible' => $item['props']['settings']['visible'],
                'disabled' => $item['props']['settings']['disabled'],
            ];
        })->toArray();

        $this->props = $props;
        $this->save();
    }

    public static function beforeShowIndex() {
        foreach(self::all() as $i => $customer)
        {
            $customer->SyncronizeField();
        }
    }

    public static function SaveDashboardSettings($customer_id, $items) {

        $customer = self::find($customer_id);

        $props = !! $customer->props ? [...$customer->props] : [];

        $customer->props = [
            ...$props,
            'dashboard' => $items,
        ];

        $customer->save();

        return $customer->props;
    }
}