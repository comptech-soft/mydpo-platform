<?php

namespace MyDpo\Models\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Livrabile\Categories\Category;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\DaysDifference;

class Chestionar extends Model {
    
    use Itemable, Actionable, DaysDifference;

    protected $table = 'chestionare';

    protected $with = ['category'];


    protected $casts = [
        'id' => 'integer',
        'props' => 'json',
        'category_id' => 'integer',
        'questions_count' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'visibility'=> 'integer',
    ];

    protected $fillable = [
        'id',
        'status',
        'name',
        'category_id',
        'questions_count',
        'visibility',
        'date_from',
        'date_to',
        'description',
        'subject',
        'body',
        'deleted',
        'props',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'visible',
        'days_difference',
        'human_status',
    ];

    public function getVisibleAttribute() {
        return [
            'color' => !! $this->visibility ? 'green' : 'red',
            'icon' => !! $this->visibility ? 'mdi-check' : 'mdi-cancel',
        ];
    }

    public function getStatusAttribute() {
        if(! $this->date_from && ! $this->date_to )
        {
            return 2;
        }
        return $this->days_difference['hours'] > 0 ? 0 : 1;
    }

    public function getHumanStatusAttribute() {
        return self::$statuses[$this->status];
    }
    
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public static function doDelete($input, $record) {

        $record->update([
            'deleted' => 1,
            'deleted_by'=> \Auth::user()->id,
            'name' => '#' . $record->id . '#',
            'props' => [
                ...(!! $record->props ? $record->props : []),
                'old_name' => $record->name, 
            ],
        ]);

        return $record;
    }

    public static function getQuery() {
        return 
            self::query()
            ->leftJoin(
                'categories',
                function($j) {
                    $j->on('categories.id', '=', 'chestionare.category_id');
                }
            )
            ->select('chestionare.*')
        ;
    }

    protected static $statuses = [
        0 => [
            'text' => 'Inactiv',
            'color' => 'red',
        ],

        1 => [
            'text' => 'Activ',
            'color' => 'blue',
        ],

        2 => [
            'text' => 'Activ (Nelimitat)',
            'color' => 'green',
        ],
    ];


}