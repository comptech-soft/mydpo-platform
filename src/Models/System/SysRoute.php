<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class SysRoute extends Model {

    use NodeTrait, Itemable, Actionable;
    
    protected $table = 'system-routes';

    protected $fillable = [
        'id',
        'slug',
        'type',
        'platform',
        'order_no',
        'name',
        'prefix',
        'middleware',
        'path',
        'verb',
        'controller',
        'method',
        'description',
        'props',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'id' => 'integer',
        'middleware' => 'json',
        'platform' => 'json',
        'props' => 'json',
        'order_no' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $with = [
        'controller',
    ];

    public function controller() {
        return $this->belongsTo(SysController::class, 'contrller_id');
    }


}