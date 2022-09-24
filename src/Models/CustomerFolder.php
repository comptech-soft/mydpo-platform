<?php

namespace MyDpo\Models;

use MyDpo\Models\Folder;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\CustomerFile;
use MyDpo\Rules\CustomerFolder\ValidName;
use MyDpo\Performers\CustomerFolder\GetAncestors;
use MyDpo\Performers\CustomerFolder\GetSummary;
use MyDpo\Performers\CustomerFolder\SaveOrderdFolders;

class CustomerFolder extends Folder {

    use NodeTrait; 
    
    protected $with = [ 
        'children',
        'files'
    ];
    
    function files() {        
        $platform = config('app.platform');

        if($platform == 'admin')
        {
            return $this->hasMany(CustomerFile::class, 'folder_id');
        }

        return $this->hasMany(CustomerFile::class, 'folder_id')
            ->whereRaw("((`customers-files`.`platform` = 'b2b') OR ( (`customers-files`.`platform` = 'admin') AND (`customers-files`.`status` <> 'protected')))");
    }

    public static function getItems($input) {
        return (new GetItems(
            $input, 
            self::query()
                ->whereRaw('((`customers-folders`.`deleted` IS NULL) OR (`customers-folders`.`deleted` = 0))')
            ,
            __CLASS__
        ))->Perform();
    }

    public static function getAncestors($input) {
        return (new GetAncestors($input))->Perform();
    }

    public static function getSummary($input) {
        return (new GetSummary($input))->Perform();
    }

    public static function saveOrderdFolders($input) {
        return (new SaveOrderdFolders($input))->Perform();
    }

    public static function GetRules($action, $input) {
        if($action == 'delete')
        {
            return NULL;
        }
        $result = [
            'customer_id' => 'required|exists:customers,id',
            'platform' => 'in:admin,b2b',
            'name' => [
                'required',
                new ValidName($input),
            ],
           
        ];
        return $result;
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    function deepDelete() {

        foreach($this->files as $file)
        {
            $file->delete();
        }
        
        $this->deleted = 1;
        $this->save();

        foreach($this->children as $child)
        {
            $child->deepDelete();
        }
    }

    public static function doDelete($input, $folder) {
        $folder->deepDelete();
        return $folder;
    }

    public static function doInsert($input, $folder) {

        if(! array_key_exists('parent_id', $input) )
        {
            $input['parent_id'] = NULL;
        } 

        if( ! $input['parent_id'] )
        {
            $folder = new self($input);
            $folder->save();
        }
        else
        {
            $parent = self::find($input['parent_id']);
            $folder = $parent->children()->create($input);
        }
    
        return $folder;
    }

}