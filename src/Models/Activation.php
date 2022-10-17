<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
// use MyDpo\Models\Curs;
// use MyDpo\Rules\Category\UniqueName;

class Activation extends Model {
   
    protected $table = 'accounts-activations';

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'user_id',
        'token',
    ];

    public static function createActivation($user_id) {

        return self::create([
            'user_id' => $user_id,
            'token' => \Str::random(64),
        ]);
    }



}