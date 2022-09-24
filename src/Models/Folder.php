<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Scopes\NotdeletedScope;

class Folder extends Model  {

    protected $table = 'customers-folders';

    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'customer_id' => 'integer',
        'default_folder_id' => 'integer',
        'deleted' => 'integer',
        'props'=> 'json',
        'parent_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'name',
        'platform',
        'default_folder_id',
        'customer_id',
        'deleted',
        'parent_id',
        'props',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function booted() {
        static::addGlobalScope( new NotdeletedScope() );
    }

}