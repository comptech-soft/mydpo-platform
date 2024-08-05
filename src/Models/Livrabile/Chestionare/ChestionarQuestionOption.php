<?php

namespace MyDpo\Models\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class ChestionarQuestionOption extends Model {

    use Actionable, Itemable;
    
    protected $table = 'chestionare-questions-options';
    
    protected $fillable = [
        'id',
        'chestionar_question_id',
        'answer_text',
        'triggered_subquestion_id',
        'is_correct',
        'is_deleted',
        'order_no',
        'props',
    ];

    protected $casts = [
        'id' => 'integer',
        'order_no' => 'integer',
        'chestionar_question_id' => 'integer',
        'triggered_subquestion_id' => 'integer',
        'is_correct' => 'integer',
        'is_deleted' => 'integer',
        'props' => 'json',
    ];

    public static function updateOption($option)
    {
        $record = self::find($option['id']);
        
        if(!! $record)
        {
            $record->update([
                'answer_text' => $option['answer_text'],
                'order_no' => $option['order_no'],
                'is_deleted' => 0,
            ]);
        }
        else
        {
            $record = self::create([
                'chestionar_question_id' => $option['chestionar_question_id'],
                'answer_text' => $option['answer_text'],
                'order_no' => $option['order_no'],
                'is_deleted' => 0,
            ]);
        }

        return $record;
    }

    public function deleteIfIsMarked()
    {
        if($this->is_deleted == 1)
        {
            $this->delete();
        }
    }
    public function markForDelete()
    {
        $this->update(['is_deleted' => 1]);

    }

}
