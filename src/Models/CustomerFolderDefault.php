<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class CustomerFolderDefault extends Model {

    use NodeTrait; 
    
    protected $fillable = [
        'id',
        'name',
        'platform',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}