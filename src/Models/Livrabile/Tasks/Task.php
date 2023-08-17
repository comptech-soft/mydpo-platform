<?php

namespace MyDpo\Models\Livrabile\Tasks;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Rules\Livrabile\Task\UniqueName;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class Task extends Model {
    
    use Itemable, Actionable;

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

    public static function GetRules($action, $input) {

        if(! in_array($action, ['insert', 'update']) )
        {
            return NULL;
        }

        $result = [
            'name' => [
                'required',
                'max:191',
                new UniqueName($action, $input),
            ],
        ];

        return $result;
    }

}