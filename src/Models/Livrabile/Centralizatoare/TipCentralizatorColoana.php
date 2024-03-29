<?php

namespace MyDpo\Models\Livrabile\Centralizatoare;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Admin\Livrabile\Tipuri\Centralizatorcolumnable;
use MyDpo\Traits\Reorderable;

class TipCentralizatorColoana extends Model {

    use Itemable, Actionable, Centralizatorcolumnable, Reorderable;
    
    protected $table = 'centralizatoare-columns';

    protected $casts = [
        'props' => 'json',
        'centralizator_id' => 'integer',
        'order_no' => 'integer',
        'is_group' => 'integer',
        'group_id' => 'integer',
        'deleted' => 'integer',
    ];

    protected $fillable = [
        'id',
        'centralizator_id',
        'order_no',
        'is_group',
        'group_id',
        'slug',
        'caption',
        'type',
        'width',
        'deleted',
        'props',
        'created_by',
        'updated_by'
    ];

    protected $with = [
        'children'
    ];

    protected $appends = [
        'array_caption',
        'not_is_clickable',
        'not_is_in_list',
        'human_type',
    ];

    public function children() {
        return $this->hasMany(TipCentralizatorColoana::class, 'group_id');
    }
}