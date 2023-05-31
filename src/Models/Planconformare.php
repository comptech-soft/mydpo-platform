<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\NextNumber;
use MyDpo\Traits\Reorderable;

class Planconformare extends Model {

    use Itemable, Actionable, NextNumber, Reorderable, NodeTrait;

    protected $table = 'plan-conformare';
    
    protected $casts = [
        'id' => 'integer',
        'order_no' => 'integer',
        'props' => 'json',
        'status' => 'json',
        'pondere' => 'decimal:2',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'actiune',
        'order_no',
        'type',
        'frecventa',
        'responsabil',
        'pondere',
        'props',
        'created_by',
        'updated_by',
    ];

    public $nextNumberColumn = 'order_no';

    protected $with = [
        'children',
    ];

    protected $appends = [
        'procent_pondere',
    ];

    public function getProcentPondereAttribute() {

        if( !! $this->children()->count() )
        {
            $sum = 0;
            foreach($this->children as $i => $child)
            {
                $sum += $child->procent_pondere;
            }
            return $sum;
        }

        return $this->pondere;
    }

    public static function doInsert($input, $record) {

        if(! $input['parent_id'])
        {
            $record = self::create($input); 
        }
        else
        {
            $parent = self::find($input['parent_id']);
            $record = $parent->children()->create($input);
        }

        return self::find($record->id);
    }

    public static function PrepareActionInput($action, $input) {

        if($action == 'insert')
        {
            $input['order_no'] = !! $input['order_no'] ? $input['order_no'] : self::GetNextFieldNumber([]); 
            $input['pondere'] = !! $input['pondere'] ? $input['pondere'] : 0; 
        }

        return $input;
    }

    public static function GetColumns() {
        return [
            [
                'id' => 1,
                'caption' => ['Capitol, acțiune, subacțiune'],
                'slug' => 'capitol',
                'width' => 400,
                'order_no' => 1,
                'type' => 'C',
            ],

            [
                'id' => 2,
                'caption' => ['Modalitate de realizare', '(discuții, raport, meeting-uri etc)'],
                'width' => 400,
                'slug' => 'modalitate-realizare',
                'order_no' => 2,
                'type' => 'C',
            ],

            [
                'id' => 3,
                'caption' => ['Frecvența', '(anuală / trimestrială / lunară)'],
                'slug' => 'frecventa',
                'width' => 200,
                'order_no' => 3,
                'type' => 'C',
            ],

            [
                'id' => 4,
                'caption' => ['Responsabil', '(DPO / persoane de care depinde realizarea acțiunii)'],
                'slug' => 'responsabil',
                'width' => 200,
                'order_no' => 4,
                'type' => 'C',
            ],

            [
                'id' => 5,
                'caption' => ['Pondere în total'],
                'slug' => 'pondere',
                'width' => 76,
                'order_no' => 5,
                'type' => 'P',
            ],

            [
                'id' => 6,
                'caption' => ['Grad de îndeplinire', 'început de an :year'],
                'slug' => 'grad-initial-an',
                'width' => 124,
                'order_no' => 6,
                'type' => 'P',
            ],

            [
                'id' => 7,
                'caption' => ['Grad de îndeplinire', 'final S1 :year'],
                'slug' => 'grad-final-s1',
                'width' => 124,
                'order_no' => 7,
                'type' => 'P',
            ],

            [
                'id' => 8,
                'caption' => ['Grad de îndeplinire', 'final S2 :year'],
                'slug' => 'grad-final-s2',
                'width' => 124,
                'order_no' => 8,
                'type' => 'P',
            ],

            [
                'id' => 9,
                'caption' => ['Total pondere realizat', 'început de an :year'],
                'slug' => 'total-realizat-an',
                'width' => 140,
                'order_no' => 9,
                'type' => 'P',
            ],

            [
                'id' => 10,
                'caption' => ['Total pondere realizat', 'final S1 :year'],
                'slug' => 'total-realizat-s1',
                'width' => 140,
                'order_no' => 10,
                'type' => 'P',
            ],

            [
                'id' => 11,
                'caption' => ['Total pondere realizat', 'final S2 :year'],
                'slug' => 'total-realizat-s2',
                'width' => 140,
                'order_no' => 11,
                'type' => 'P',
            ],

            [
                'id' => 12,
                'caption' => ['Observații'],
                'slug' => 'observatii',
                'width' => 400,
                'order_no' => 12,
                'type' => 'C',
            ],

            [
                'id' => 13,
                'caption' => null,
                'slug' => null,
                'width' => null,
                'order_no' => 13,
                'type' => 'C',
            ],
        ];
    }

     


} 