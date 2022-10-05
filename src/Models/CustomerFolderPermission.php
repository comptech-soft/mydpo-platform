<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;

class CustomerFolderPermission extends Model {

    protected $table = 'customers-folders-permissions';
    
    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'customer_id' => 'integer',
        'folder_id' => 'integer',
        'user_id' => 'integer',
        'has_access' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'folder_id',
        'user_id',
        'has_access',
        'updated_by',
        'deleted_by',
        'created_by',
    ];

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }
}