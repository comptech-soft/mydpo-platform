<?php

namespace MyDpo\Models\Nomenclatoare\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class QuestionAnswer extends Model {

    use Actionable, Itemable;
    
    protected $table = 'questions-collection-answers';
    
    protected $fillable = [
        'id',
        'question_id',
        'answer_text',
        'triggered_subquestion_id',
        'is_correct',
        'order_no',
        'deleted',
        'props',
        'source_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'order_no' => 'integer',
        'source_id' => 'integer',
        'question_id' => 'integer',
        'deleted' => 'integer',
        'triggered_subquestion_id' => 'integer',
        'is_correct' => 'integer',
        'props' => 'json',
    ];


}
