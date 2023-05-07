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
        'body' => 'json',
        'status' => 'json',
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
            ->select([
                'centralizatoare.id', 
                'centralizatoare.name', 
                'centralizatoare.category_id', 
                'centralizatoare.description', 
                'centralizatoare.body', 
                'centralizatoare.status'
            ]);
        ;
    }

    public static function doInsert($input, $record) {

        $record = self::create($input);

        foreach($input['body'] as $key => $value)
        {
            if($value == 1)
            {
                $record->{'AddColumn' . ucfirst($key)}();
            }
        }
    }

    public function AddColumnVizibilitate() {

        $this->AddColumn('Vizibilitate', 'VISIBILITY', -100, 100);
        
        
    }

    public function AddColumn($caption, $type, $order_no, $width) {
        
        $input = [
            'centralizator_id' => $this->id,
            'caption' => $caption,
            'slug' => md5($caption . time()),
            'is_group' => 0,
            'group_id' => NULL,
            'type' =>  $type,
            'order_no' => $order_no,
            'width' => $width,
        ];

        $record = CentralizatorColoana::where('centralizator_id', $this->id)->where('type', 'VISIBILITY')->first();

        if(!! $record)
        {
            $record->update($input);
        }
        else
        {
            $record = CentralizatorColoana::create($input);
        }

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