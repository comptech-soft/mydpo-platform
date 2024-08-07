<?php

namespace MyDpo\Models\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Nomenclatoare\Livrabile\Chestionare\TipIntrebare;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Reorderable;
use MyDpo\Observers\Livrabile\Chestionare\ChestionarQuestionObserver;


class ChestionarQuestion extends Model {
    
    use Itemable, Actionable, NodeTrait, Reorderable;

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
        'children',
        'options'
    ];

    public function tip() {
        return $this->belongsTo(TipIntrebare::class, 'question_type_id');
    }

    public function chestionar() {
        return $this->belongsTo(Chestionar::class, 'chestionar_id');
    }

    public function options() {
        return $this->hasMany(ChestionarQuestionOption::class, 'chestionar_question_id')->orderBy('order_no');
    }

    /**
     * adaugarea unei intrebari la chestionar
     */
    public static function doInsert($input, $record)
    {

        if(!! $input['parent_id'])
        {
            $parent = self::find($input['parent_id']);

            $record = $parent->children()->create($input);
        }
        else
        {
            $record = self::create($input);
        }

        $record->syncOptions( array_key_exists('options', $input)  ? $input['options'] : [] );
        
        return self::find($record->id);
    }

    public static function doUpdate($input, $record)
    {
        $record->update($input);

        $record->syncOptions( array_key_exists('options', $input)  ? $input['options'] : [] );

        return self::find($record->id);
    }

    protected function syncOptions($options)
    {
        $this->markOptionsForDelete();
        
        $this->updateOptions($options);   

        $this->deleteMarkedOptions();

    }

    protected function updateOptions($options)
    {
        foreach($options as $i => $option)
        {
            ChestionarQuestionOption::updateOption([
                ...$option,
                'chestionar_question_id' => $this->id,
            ]);
        }

        $this->refresh();
    }

    protected function deleteMarkedOptions()
    {
        foreach($this->options as $i => $record)
        {
            $record->deleteIfIsMarked();
        }

        $this->refresh();
    }

    protected function markOptionsForDelete()
    {
        foreach($this->options as $i => $record)
        {
            $record->markForDelete();
        }

        $this->refresh();
    }

    protected static function booted() 
    {
        static::observe(ChestionarQuestionObserver::class);
    }
}