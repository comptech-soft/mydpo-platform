<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Traits\Itemable;

class SysAction extends Model {

    use NodeTrait, Itemable;
    
    protected $table = 'system-actions';

    protected $fillable = [
        'id',
        'slug',
        'type',
        'platform',
        'order_no',
        'caption',
        'description',
        'props',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'id' => 'integer',
        'platform' => 'json',
        'props' => 'json',
        'order_no' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $appends = [
        'name',
    ];

    protected $with = [
        'children',
        'roles',
    ];

    public function getNameAttribute() {
        return $this->caption;
    }

    function roles() {
        return $this->hasMany(SysActionRole::class, 'action_id');
    }

    public static function GetBySlug($slug) {
        return self::whereSlug($slug)->first();
    }
}