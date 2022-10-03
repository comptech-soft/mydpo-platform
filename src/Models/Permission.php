<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;

// https://github.com/lazychaser/laravel-nestedset#installation

class Permission extends Model {
    use NodeTrait;
    
    protected $table = 'permissions';

    // protected $with = ['ancestors'];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'platform',
        'description',
        'order_no',
        'props',
        'deleted',
        'created_by',
        'updated_by',
    ];

    public static function GetRules($action, $input) {
        if($action == 'delete')
        {
            return NULL;
        }
        $result = [
            'name' => [
                'required',
                'unique:permissions,name'
            ],
           
        ];
        return $result;
    }

    public static function doInsert($input, $folder) {

        if(! array_key_exists('parent_id', $input) )
        {
            $input['parent_id'] = NULL;
        } 

        if( ! $input['parent_id'] )
        {
            $permission = new self($input);
            $permission->save();
        }
        else
        {
            $parent = self::find($input['parent_id']);
            $permission = $parent->children()->create($input);
        }
    
        return $permission;
    }

    public static function doAction($action, $input) {
        $input['slug'] = \Str::slug($input['name']);
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

}
