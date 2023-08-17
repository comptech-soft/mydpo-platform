<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Rules\Nomenclatoare\Country\UniqueName;

class Country extends Model {

    use Itemable, Actionable;
    
    protected $table = 'countries';

    protected $fillable = [
        'id',
        'name',
        'uuid',
        'code',
        'vat_prefix',
        'phone_prefix',
        'created_by',
        'updated_by'
    ]; 

    function regions() {
        return $this->hasMany(Region::class, 'country_id');
    }

    public static function GetQuery() {
        return self::query()->select(['id', 'name', 'code', 'vat_prefix', 'phone_prefix'])->withCount('regions');
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