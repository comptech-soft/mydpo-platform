<?php

namespace MyDpo\Models\Nomenclatoare\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

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
        'is_numeric',
        'can_has_subquestion',
    ];

    protected $casts = [
        'id' => 'integer',
        'order_no' => 'integer',
        'has_answers' => 'integer',
        'can_has_subqestion' => 'integer',
        'is_numeric' => 'integer',
    ];

    public static function findByShortName(?string $short_name): ?TipIntrebare
    {
        return self::where('short_name', $short_name)->first();
    }

}