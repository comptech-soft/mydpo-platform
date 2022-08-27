<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;

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


}