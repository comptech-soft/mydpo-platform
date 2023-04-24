<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Traits\Itemable;
use MyDpo\Rules\Permission\UniquePermission;

// https://github.com/lazychaser/laravel-nestedset#installation

class Permission extends Model {

    use NodeTrait, Itemable;
    
    protected $table = 'permissions';

    protected $with = ['children'];

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
                new UniquePermission($input),
            ],
           
        ];
        return $result;
    }

    public static function doInsert($input, $permission) {

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
            $input['slug'] = $parent->slug . '#' . $input['slug'];
            $permission = $parent->children()->create($input);
        }
    
        return $permission;
    }

    public static function doAction($action, $input) {
        $input['slug'] = \Str::slug($input['name']);
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query(), __CLASS__))->Perform();
    // }

}
