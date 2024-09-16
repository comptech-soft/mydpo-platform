<?php

namespace MyDpo\Models\Livrabile\Categories;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Rules\Livrabile\Category\UniqueName;

class Category extends Model {
    
    use Itemable, Actionable;

    protected $table = 'categories';

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
        'type',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function CreateIfNotExists(string $name, string $type)
    {
        $record = self::whereName($name)->whereType($type)->first();

        if(!! $record )
        {
            return $record;
        }

        return self::create([
            'name' => $name,
            'type' => $type,
        ]);
    }

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
            
            'type' => 'in:centralizatoare,chestionare,cursuri,registre',
        ];

        return $result;
    }

    public static function GetMessages($action, $input) {
        return [
            'type.in' => 'Tipul trebuie selectat.',
        ];
    }

}