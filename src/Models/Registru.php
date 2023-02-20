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

    protected $appends = [
        'columns',
    ];

    public function getColumnsAttribute() {
        $t = $this->coloane->filter( function($item) {
            if($item->is_group == 1)
            {
                return TRUE;
            }

            if($item->is_group == 0 && $item->group_id == 0)
            {
                return TRUE;
            }
            return FALSE;
        })->map(function($item) {
            $item->column_type = $item->is_group == 1 ? 'group' : 'single';
            return $item;
        })->sortBy('order_no')->toArray();

        $r = [];
        foreach($t as $i => $item)
        {
            $r[]  = $item;
        }
       
        foreach($r as $i => $record)
        {
            $r[$i]['children'] = [];
            if($record['is_group'] == 1)
            {
                $children = $this->coloane->filter( function($item) use ($record) {
                    if($item->group_id == $record['id'])
                    {
                        return TRUE;
                    }
                    return FALSE;
                })->sortBy('order_no')->toArray();

                foreach($children as $j => $child)
                {
                    $r[$i]['children'][] = $child;
                }
            }
        }

        return $r;
    }


    function coloane() {
        return $this->hasMany(RegistruColoana::class, 'register_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query()->with(['coloane']), __CLASS__))->Perform();
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