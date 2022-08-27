<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;

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

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }


}