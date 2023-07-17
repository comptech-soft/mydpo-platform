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

    public static function CreateOrUpdate($input) {

        $record = self::where('action_id', $input['action_id'])
            ->where('role_id', $input['role_id'])
            ->where('platform', $input['platform']);
        
        if(array_key_exists('customer_id', $input) && $input['customer_id'])
        {
            $record = $record->where('customer_id', $input['customer_id']);
        }
        
        $record = $record->first();

        if($record)
        {
            $record->update($input);
        }
        else
        {
            $record = self::create($input);
        }

        return $record;

    }
}