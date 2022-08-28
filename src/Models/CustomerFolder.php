<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\CustomerFile;

class CustomerFolder extends Model {

    use NodeTrait; 
    
    protected $table = 'customers-folders';

    protected $with = ['children', 'files'];

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

    function files() {
        return $this->hasMany(CustomerFile::class, 'folder_id');
    }

    public static function getItems($input) {
        return (new GetItems(
            $input, 
            self::query()
                ->whereRaw('((`customers-folders`.`deleted` IS NULL) OR (`customers-folders`.`deleted` = 0))'), 
            __CLASS__
        ))->Perform();
    }


}