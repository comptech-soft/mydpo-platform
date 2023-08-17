<?php

namespace MyDpo\Models\Livrabile\Categories;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Actions\Items\Dataprovider;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
// use MyDpo\Rules\Category\UniqueName;
// use MyDpo\Traits\Itemable;

class Task extends Model {
    
    // use Itemable;

    protected $table = 'tasks';

    // protected $casts = [
    //     'id' => 'integer',
    //     'created_by' => 'integer',
    //     'updated_by' => 'integer',
    //     'deleted_by' => 'integer',
    //     'deleted' => 'integer',
    // ];

    // protected $fillable = [
    //     'id',
    //     'name',
    //     'type',
    //     'deleted',
    //     'created_by',
    //     'updated_by',
    //     'deleted_by'
    // ];

    // function centralizatoare() {
    //     return $this->hasMany(Centralizator::class, 'category_id');
    // }

    // function cursuri() {
    //     return $this->hasMany(Curs::class, 'category_id');
    // }

    // public static function getItems($input, $type = NULL) {
    //     if(! $type )
    //     {
    //         return (new GetItems($input, self::query()->select(['id', 'name', 'type']), __CLASS__))->Perform();
    //     }

    //     if($type == 'centralizatoare')
    //     {
    //         return (new Dataprovider($input, self::query()->select(['id', 'name'])->where('type', $type), __CLASS__))->Perform();
    //     }

    //     return (new GetItems(
    //         $input, 
    //         self::query()->select(['id', 'name'])->where('type', $type)->withCount($type), 
    //         __CLASS__
    //     ))->Perform();
    // }

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }

    // public static function doDelete($input, $record) {

    //     $record->deleted = 1;
    //     $record->deleted_by = \Auth::user()->id;

    //     $record->name = '#' . $record->id . '-' . $record->name;

    //     $record->save();
    //     return $record;
    // }

    // public static function isValidName($input) {
    //     $validator = \Validator::make($input, self::GetRules('insert', $input) );
    //     return $validator->fails() ? 0 : 1;
    // }

    // public static function GetRules($action, $input) {

    //     if($action == 'delete')
    //     {
    //         return NULL;
    //     }

    //     $result = [
    //         'name' => [
    //             'required',
    //             'max:191',
    //             new UniqueName($input),
    //         ],
    //         'type' => 'in:centralizatoare,chestionare,cursuri,registre',
    //     ];

    //     return $result;
    // }



}