<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;

class Service extends Model {

    
    protected $table = 'services';

    protected $casts = [
        'abonamente' => 'json',
        'tarif' => 'float',
        'tarif_ore_suplimentare' => 'float',
        'is_abonament' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'ore_incluse_abonament' => 'integer', 
        'order_no' => 'integer', 
    ];

    protected $fillable = [
        'id',
        'name',
        'type',
        'order_no',
        'tarif',
        'tarif_ore_suplimentare',
        'is_abonament',
        'abonamente',
        'ore_incluse_abonament',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function GetRules($action, $input) {

        if($action == 'delete')
        {
            return NULL;
        }
        
        $result = [
            'type' => 'required|exists:services-types,slug',
            
            'name' => 'required',
            
            // 'category_id' => 'required|exists:categories,id',
        ];

        

        return $result;
    }

}