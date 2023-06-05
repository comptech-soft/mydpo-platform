<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;

class SysRole extends Model {

    use Itemable;

    protected $table = 'roles';

    protected $casts = [
        'id' => 'integer',
        'permissions' => 'json',
        'editable' => 'integer',
        'deletable' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'permissions',
        'type',
        'color',
        'editable',
        'deleteabe',
        'created_by',
        'updated_by'
    ];

}