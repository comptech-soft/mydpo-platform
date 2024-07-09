<?php

namespace MyDpo\Models\Nomenclatoare\Livrabile\ELearning;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Rules\Nomenclatoare\Livrabile\Cursuri\Cursadresare\UniqueName;

class Adresare extends Model {
    
    use Itemable, Actionable;
    
    protected $table = 'cursuri-adresare';

    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
    ];

    protected $fillable = [
        'id',
        'name',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

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