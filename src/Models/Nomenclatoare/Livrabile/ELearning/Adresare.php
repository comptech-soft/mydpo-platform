<?php

namespace MyDpo\Models\Nomenclatoare\Livrabile\ELearning;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;

class Adresare extends Model {
    
    use Itemable;
    
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

    // function cursuri() {
    //     return $this->hasMany(Curs::class, 'adresare_id');
    // }

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query()->withCount('cursuri'), __CLASS__))->Perform();
    // }

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }

    // public static function GetRules($action, $input) {

    //     if($action == 'delete')
    //     {
    //         return NULL;
    //     }

    //     $result = [
    //         'name' => [
    //             'required',
    //             'max:191',
    //             new UniqueName($input),
    //         ],
    //     ];

    //     return $result;
    // }



}