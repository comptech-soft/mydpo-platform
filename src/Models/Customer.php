<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model {

    protected $casts = [
        'logo' => 'json',
        'city_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    // protected $with = ['city.region.country', 'contracts.orders.services.service', 'persons.user', 'departments'];

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

}