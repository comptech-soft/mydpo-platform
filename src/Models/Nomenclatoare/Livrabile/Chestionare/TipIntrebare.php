<?php

namespace MyDpo\Models\Nomenclatoare\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Models\Livrabile\Categories\Category;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
// use MyDpo\Traits\Admin\Livrabile\Tipuri\Centralizatorable;
// use MyDpo\Scopes\NotdeletedScope;
// use MyDpo\Models\Customer\Centralizatoare\Centralizator;

class TipIntrebare extends Model {

    use Itemable, Actionable;

    protected $table = 'question-types';
    
    protected $fillable = [
        'id',
        'long_name',
        'short_name',
        'order_no',
        'icon',
        'has_answers',
        'can_has_subquestion',
    ];

    protected $casts = [
        'id' => 'integer',
        'order_no' => 'integer',
        'has_answers' => 'integer',
        'can_has_subqestion' => 'integer',
    ];

   
}