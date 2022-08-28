<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Customer;

class CustomerStatus extends Model {

    protected $table = 'customers-statuses';

    protected $casts = [
        'props' => 'json',
    ];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'props',
    ];

    protected $appends = [
        'initial',
    ];

    public function getInitialAttribute() {
        return strtoupper($this->name[0]);
    }

    function customers() {
        return $this->hasMany(Customer::class, 'slug', 'status');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query()->withCount('customers'), __CLASS__))->Perform();
    }


}