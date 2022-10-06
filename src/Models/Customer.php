<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\CustomerStatus;
use MyDpo\Models\City;
use MyDpo\Models\CustomerContract;
use MyDpo\Models\CustomerAccount;
use MyDpo\Models\CustomerFolder;
use MyDpo\Models\UserCustomer;

class Customer extends Model {

    protected $table = 'customers';

    protected $casts = [
        'logo' => 'json',
        'city_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $with = [
        'mystatus', 
        'city.region.country', 
        //'contracts.orders.services.service', 'persons.user', 'departments'
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
        'newsletter',
        'locale',
        'status',
        'details',
        'city_id',
        'logo',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'full_city',
        'region_id',
        'country_id',
        'last_contract',
        'mylogo',
    ];

    public function getMylogoAttribute() {
        
        if( ! $this->logo )
        {
            return NULL;
        }

        return $this->logo['url'];
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
        return $this->belongsTo(CustomerStatus::class, 'status', 'slug');
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

    public function createDefaultFolder($defaultFolder, $parent) {

        if(! $parent )
        {
            $folder = CustomerFolder::where('customer_id', $this->id)->where('name', $defaultFolder->name)->first();
        }
        else
        {
            $folder = CustomerFolder::where('customer_id', $this->id)->where('name', $defaultFolder->name)->where('parent_id', $parent->id)->first();
        }

        $input = [
            'name' => $defaultFolder->name,
            'customer_id' => $this->id,
            'default_folder_id' => $defaultFolder->id,
            'platform' => 'admin',
            'props' => [
                'defaultfolder' => $defaultFolder, 
            ],
            'deleted' => 0,
        ];

        if( ! $folder )
        {
            if(! $parent )
            {
                $folder = CustomerFolder::create($input);
            }
            else
            {
                $parent->children()->create($input);
            }  
        }
        else
        {
            $folder->update($input);
        }

        if($defaultFolder->children->count())
        {
            foreach($defaultFolder->children as $i => $child) 
            {
                $this->createDefaultFolder($child, $folder);
            }
        }
    }

    public function createDefaultFolders() {
        $defaultFolders = CustomerFolderDefault::whereNull('parent_id')->get();  

        foreach($defaultFolders as $i => $defaultFolder) {
            $this->createDefaultFolder($defaultFolder, NULL);
        }
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function getItemsWithPersons($input) {
        return (new GetItems($input, self::query()->whereHas('accounts')->with(['accounts']), __CLASS__))->Perform();
    }
    
    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
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

    

}