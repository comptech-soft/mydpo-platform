<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Rules\Nomenclatoare\City\UniqueName;

class City extends Model {

    use Itemable, Actionable;
    
    protected $table = 'cities';

    protected $fillable = [
        'id',
        'uuid',
        'name',
        'postal_code',
        'siruta',
        'latitude',
        'longitude',
        'region_id',
        'created_by',
        'updated_by'
    ];

    public function region() {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public static function GetQuery() {
        return self::query()->select(['id', 'name', 'postal_code', 'region_id']);
    }

    public static function GetRules($action, $input) {

        if(! in_array($action, ['insert', 'update']) )
        {
            return NULL;
        }

        $result = [
            'name' => [
                'required',
                'max:191',
                new UniqueName($action, $input),
            ],

        ];

        return $result;
    }
    
}