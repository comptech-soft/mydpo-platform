<?php

namespace MyDpo\Models\Nomenclatoare\Livrabile\ELearning;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;

class Type extends Model {

    use Itemable;
   
    protected $table = 'cursuri-types';

    protected $casts = [
        'id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'props',
    ];

    // function cursuri() {
    //     return $this->hasMany(Curs::class, 'type', 'slug');
    // }


}