<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model {

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