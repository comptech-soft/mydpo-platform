<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Models\Category;
use MyDpo\Scopes\NotdeletedScope;

class Centralizator extends Model {

    use Itemable, Actionable;

    protected $table = 'centralizatoare';

    protected $with = ['category'];

    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'status',
        'name',
        'category_id',
        'description',
        'subject',
        'body',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function booted() {
        static::addGlobalScope( new NotdeletedScope() );
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id')->select(['id', 'name']);
    }

    public static function GetQuery() {
        return 
            self::query()
            ->leftJoin(
                'categories',
                function($j) {
                    $j->on('categories.id', '=', 'centralizatoare.category_id');
                }
            )
            ->select(['centralizatoare.id', 'centralizatoare.name', 'centralizatoare.category_id', 'centralizatoare.description'])
        ;
    }

    public static function doDelete($input, $record) {

        CentralizatorColoana::where('centralizator_id', $record->id)->delete();

        $record->deleted = 1;
        $record->deleted_by = \Auth::user()->id;
        $record->save();

        return $record;

    }

    public static function GetRules($action, $input) {


        if($action == 'delete')
        {
            return NULL;
        }
        $result = [
            'name' => [
                'required',
                // new UniquePermission($input),
            ],
            'category_id' => [
                'required',
            ],
           
        ];
        return $result;
    }

}