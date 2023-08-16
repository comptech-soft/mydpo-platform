<?php

namespace MyDpo\Models\Livrabile\Registre;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Admin\Livrabile\Tipuri\Centralizatorcolumnable;
use MyDpo\Traits\Reorderable;

class TipRegistruColoana extends Model {

    use Itemable, Actionable, Reorderable, Centralizatorcolumnable;

    protected $table = 'registers-columns';

    protected $casts = [
        'props' => 'json',
        'register_id' => 'integer',
        'order_no' => 'integer',
        'is_group' => 'integer',
        'group_id' => 'integer',
        'deleted' => 'integer',
    ];

    protected $fillable = [
        'id',
        'register_id',
        'order_no',
        'is_group',
        'group_id',
        'slug',
        'caption',
        'type',
        'width',
        'decimals',
        'deleted',
        'suffix',
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
        return $this->hasMany(TipRegistruColoana::class, 'group_id');
    }

}