<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Scopes\FolderScope;

class Folder extends Model  {

    protected $table = 'customers-folders';

    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'customer_id' => 'integer',
        'deleted' => 'integer',
        'parent_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'name',
        'platform',
        'customer_id',
        'deleted',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function booted() {
        static::addGlobalScope( new FolderScope );
    }

}