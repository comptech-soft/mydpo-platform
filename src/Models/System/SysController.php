<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;

class SysController extends Model {
   
    protected $table = 'system-controllers';

    protected $fillable = [
        'id',
        'namespace',
        'controller'
    ];

    protected $casts = [
        'id' => 'integer',
    ];

}