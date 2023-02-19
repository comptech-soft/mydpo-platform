<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;

class Registru extends Model {

    protected $table = 'registers';

    protected $casts = [
        'props' => 'json',
        'order_no' => 'integer',
        'allow_upload_row_files' => 'integer',
        'has_departamente_column' => 'integer',
        'allow_versions' => 'integer',
        'has_status_column' => 'integer',
        'upload_folder_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'order_no',
        'allow_upload_row_files',
        'allow_versions',
        'upload_folder_id',
        'has_departamente_column',
        'has_status_column',
        'description',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    
    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function GetRules($action, $input) {
        if($action == 'delete')
        {
            return NULL;
        }
        $result = [
        ];

        return $result;
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

}