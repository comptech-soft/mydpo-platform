<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\CustomerStatus;
use MyDpo\Models\City;

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
    ];

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

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
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

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

}