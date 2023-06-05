<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;

class SysActionRole extends Model {

   
    protected $table = 'system-actions-roles';

    protected $fillable = [
        'id',
        'action_id',
        'role_id',
        'customer_id',
        'platform',
        'visible',
        'disabled',
    ];

    protected $casts = [
        'id' => 'integer',
        'action_id' => 'integer',
        'role_id' => 'integer',
        'customer_id' => 'integer',
        'visible' => 'integer',
        'disabled' => 'integer',
    ];

    public function action() {
        return $this->belongsTo(SysAction::class, 'action_id');
    }

    public function role() {
        return $this->belongsTo(SysRole::class, 'role_id')->select(['id', 'slug', 'name', 'color']);
    }
}