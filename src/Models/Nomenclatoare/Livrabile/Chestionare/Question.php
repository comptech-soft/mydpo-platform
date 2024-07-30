<?php

namespace MyDpo\Models\Nomenclatoare\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Rules\Nomenclatoare\Livrabile\Chestionare\Question\UniqueName;
class Question extends Model {

    use Itemable, Actionable, NodeTrait;

    protected $table = 'questions-collection';
    
    protected $fillable = [
        'id',
        'order_no',
        'question_type_id',
        'name',
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

    protected $casts = [
        'id' => 'integer',
        'order_no' => 'integer',
        'question_type_id' => 'integer',
        'activate_on_answer_id' => 'integer',
        'score' => 'integer',
        'time_limit' => 'integer',
        'is_required' => 'integer',
    ];

    protected $with = [
        'tip'
    ];

    public function tip() {
        return $this->belongsTo(TipIntrebare::class, 'question_type_id');
    }

    public static function doUpdate($input, $record) {

        dd($input, $record);
    }


    public static function GetRules($action, $input) {

        if(! in_array($action, ['insert', 'update']) )
        {
            return NULL;
        }

        $result = [
            'name' => [
                'required',
                'max:128',
                new UniqueName($action, $input),
            ],

            'question_text' => [
                'required',
            ],
        ];

        return $result;
    }
}