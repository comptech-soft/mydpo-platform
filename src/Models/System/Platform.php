<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model {

    protected $table = 'platforms';

    protected $fillable = [
        'id',
        'slug',
        'name',              
        'url',
    ];

}