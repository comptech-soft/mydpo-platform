<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\CustomerFile;
use MyDpo\Rules\CustomerFolder\ValidName;

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

    public static function doAction($action, $input) {
        if( ! $input['platform'] )
        {
            $input['platform'] = config('app.platform');
        }
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }


}