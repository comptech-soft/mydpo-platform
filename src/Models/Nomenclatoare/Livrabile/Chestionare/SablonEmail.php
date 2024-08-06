<?php

namespace MyDpo\Models\Nomenclatoare\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class SablonEmail extends Model {

    use Itemable, Actionable;

    protected $table = 'chestionare-template-emails';
    
    protected $fillable = [
        'id',
        'subject',
        'body',
    ];

   
}