<?php

namespace MyDpo\Models\Livrabile\Tasks;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Actions\Items\Dataprovider;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
// use MyDpo\Rules\Category\UniqueName;
use MyDpo\Traits\Itemable;

class Task extends Model {
    
    use Itemable;

    protected $table = 'tasks';

    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
    ];

    protected $fillable = [
        'id',
        'name',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    
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