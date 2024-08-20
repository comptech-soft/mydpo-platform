<?php

namespace MyDpo\Models\Nomenclatoare\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Rules\Nomenclatoare\Livrabile\Chestionare\Question\UniqueName;
use MyDpo\Observers\Nomenclatoare\Livrabile\Chestionare\QuestionObserver;

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
        'props' => 'json',
    ];

    protected $with = [
        'tip',
        'answers',
        'activator'
    ];

    public function tip() {
        return $this->belongsTo(TipIntrebare::class, 'question_type_id');
    }

    public function activator() {
        return $this->belongsTo(QuestionAnswer::class, 'activate_on_answer_id');
    }

    public function answers() {
        return $this->hasMany(QuestionAnswer::class, 'question_id')->orderBy('order_no');
    }

    public static function doInsert($input, $record)
    {
        $record = self::create( collect($input)->except(['options'])->toArray() );

        if( array_key_exists('options', $input) )
        {
            $record->attachOptions( $input['options'] );
        }

        return $record;
    }

    public static function doAttachsubquestion($input, $record) {

        $child = self::find($input['child_id']);

        $child->update([
            'parent_id' => $input['parent_id'],
            'activate_on_answer_id' => $input['activate_on_answer_id'],
        ]);

        return $child;
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

    public static function GetQuery()
    {
        return self::query()->with(['ancestors'])->withCount(['children']);
    }

    protected static function booted() 
    {
        static::observe(QuestionObserver::class);
    }
}