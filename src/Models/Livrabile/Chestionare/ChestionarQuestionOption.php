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
        'order_no',
        'props',
    ];

    protected $casts = [
        'id' => 'integer',
        'order_no' => 'integer',
        'chestionar_question_id' => 'integer',
        'triggered_subquestion_id' => 'integer',
        'is_correct' => 'integer',
        'props' => 'json',
    ];

    public static function updateOption($option)
    {
        $record = self::find($option['id']);
        
        if(!! $record)
        {
            $props = !! $record->props ? $record->props : [];
            $props['deleted'] = 0;

            $record->answer_text = $option['answer_text'];
            $record->order_no = $option['order_no'];
            $record->props = $props;
            $record->save();
        }
        else
        {
            $props['deleted'] = 0;

            $record = self::create([
                'chestionar_question_id' => $option['chestionar_question_id'],
                'answer_text' => $option['answer_text'],
                'order_no' => $option['order_no'],
                'props' => $props
            ]);
        }

        return $record;
    }

    public function deleteIfIsMarked()
    {
        if(!! $this->props )
        {
            if( array_key_exists('deleted', $this->props))
            {
                if($this->props['deleted'] == 1)
                {
                    $this->delete();
                }
            }
        }
    }
    public function markForDelete()
    {
        $props = !! $this->props ? $this->props : [];

        $this->update([
            'props' => [
                ...$props,
                'deleted' => 1,
            ],
        ]);
    }


}
