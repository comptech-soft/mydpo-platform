<?php

namespace MyDpo\Models\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Nomenclatoare\Livrabile\Chestionare\TipIntrebare;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;


class ChestionarQuestion extends Model {
    
    use Itemable, Actionable, NodeTrait;

    protected $table = 'chestionare-questions';


    protected $casts = [
        'id' => 'integer',
        'order_no' => 'integer',
        'chestionar_id' => 'integer',
        'question_type_id' => 'integer',
        'activate_on_answer_id' => 'integer',
        'score' => 'integer',
        'time_limit' => 'integer',
        'is_required' => 'integer',
    ];

    protected $fillable = [
        'id',
        'chestionar_id',
        'order_no',
        'question_type_id',
        'question_text',
        'activate_on_answer_id',
        'score',
        'time_limit',
        'is_required',
        'other_text',
        'none_text',
        'keywords',
        'correct_answers',
        'type',
        'correct_explanation',
        'incorrect_explanation',
        'props',
    ];

    protected $with = [
        'tip',
        // 'answers',
        // 'activator'
    ];


    public function tip() {
        return $this->belongsTo(TipIntrebare::class, 'question_type_id');
    }




}