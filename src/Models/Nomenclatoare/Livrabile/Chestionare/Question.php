<?php

namespace MyDpo\Models\Nomenclatoare\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Rules\Nomenclatoare\Livrabile\Chestionare\Question\UniqueName;
use MyDpo\Observers\Nomenclatoare\Livrabile\Chestionare\QuestionObserver;
use MyDpo\Models\Livrabile\Chestionare\ChestionarQuestion;

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
        'parent_id',
        'source_id',
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
        'parent_id' => 'integer',
        'source_id' => 'integer',
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

    public static function updateInCollection(ChestionarQuestion $record)
    {
        $collection = self::where('source_id', $record->id)->first();

        if( ! $collection )
        {
            return NULL;
        }
        
        $input = collect($record->toArray())
            ->except(['parent_id', 'chestionar', 'id', 'name', 'options', '_lft', '_rgt', 'created_at', 'updated_at'])
            ->toArray();

        /**
         * Verificam daca este subintrebare 
         * $parent = intrebarea parinte din 'questions-collection'
         */
        $parent = NULL;
        $activate_on_answer_id = NULL;

        if(!! $record->parent_id)
        {
            /**
             * Avem o subintrebare
             * Se activeaza pentru $record->activate_on_answer_id dim 'chestionare-questions'
             */
            $parent = self::where('source_id', $record->parent_id)->first();

            if(!! $parent)
            {
                $activator = $parent->answers->where('source_id', $record->activate_on_answer_id)->first();

                if(!! $activator )
                {
                    $activate_on_answer_id = $activator->id;
                }
            }

        }
        else
        {
            $parent = NULL;
        }

        $options = $record->options->map(function($option, $i) use ($collection) {

            $exists = $collection->answers()->where('source_id', $option->id)->first();

            return [
                'id' => !! $exists ? $exists->id : -1,
                'answer_text' => $option->answer_text,
                'source_id' => $option->id,
                'order_no' => 1 + $i
            ];
        })->toArray();

        return self::doUpdate(
            [
                ...$input,
                'id' => $collection->id,
                'name' => '#' . $record->id,
                'options' =>  $options,
                'parent_id' => !! $parent ? $parent->id : NULL,
                'activate_on_answer_id' => $activate_on_answer_id,
            ], 

            $collection
        );
    }

    public static function addToCollection(ChestionarQuestion $record)
    {

        /**
         * $record = intrebarea ve a fost definita in Chestionar 
         * si trebuie adaugata la colectie
         */
        $input = collect($record->toArray())
            ->except(['parent_id', 'chestionar', 'id', 'name', 'options', '_lft', '_rgt', 'created_at', 'updated_at'])
            ->toArray();
        
        /**
         * Verificam daca este subintrebare 
         * $parent = intrebarea parinte din 'questions-collection'
         */
        $parent = NULL;
        $activate_on_answer_id = NULL;

        if(!! $record->parent_id)
        {
            /**
             * Avem o subintrebare
             * Se activeaza pentru $record->activate_on_answer_id din 'chestionare-questions'
             */
            $parent = self::where('source_id', $record->parent_id)->first();

            if(!! $parent )
            {
                $activator = $parent->answers->where('source_id', $record->activate_on_answer_id)->first();

                if(!! $activator )
                {
                    $activate_on_answer_id = $activator->id;
                }

            }
        }

        $options = $record->options->map(function($option, $i) use ($activate_on_answer_id){
            return [
                'id' => -1,
                'source_id' => $option->id,
                'answer_text' => $option->answer_text,
                'order_no' => 1 + $i
            ];
        })->toArray();
        
        return self::doInsert(
            [
                ...$input,
                'id' => NULL,
                'name' => '#' . $record->id,
                'source_id' => $record->id,
                'options' =>  $options,
                'parent_id' => !! $parent ? $parent->id : NULL,
                'activate_on_answer_id' => $activate_on_answer_id,
            ], 

            NULL
        );

    }

    public static function doInsert($input, $record)
    {
        $record = self::create( collect($input)->except(['options', 'answers'])->toArray() );

        if( array_key_exists('options', $input) )
        {
            $record->attachOptions( $input['options'] );
        }

        if( array_key_exists('answers', $input) )
        {
            $record->attachOptions( $input['answers'] );
        }

        return $record;
    }

    public static function doUpdate($input, $record)
    {

        $record->update( collect($input)->except(['options', 'answers'])->toArray() );

        if( array_key_exists('options', $input) )
        {
            $record->attachOptions( $input['options'] );
        }

        if( array_key_exists('answers', $input) )
        {
            $record->attachOptions( $input['answers'] );
        }

        return $record;
    }

    public function attachOptions($options)
    {

        $this->answers()->update(['deleted' => 1]);

        $options = collect($options)->map( function($item) {
            return [
                ...$item,
                'id' => ($item['id'] < 0 ? NULL : $item['id']),
                'question_id' => $this->id,
                'deleted' => 0,
            ];
        })->toArray();

        foreach( $options as $i => $option)
        {
            if( ! $option['id'] )
            {
                $record = QuestionAnswer::create($option);
            }
            else
            {
                $record = QuestionAnswer::find( $option['id'] );
                if(! $record )
                {
                    $record = QuestionAnswer::create($option);
                }
                else
                {
                    $record->update($option);
                }
            }
        }
        $this->answers()->where('deleted', 1)->delete();
    }

    public static function doAttachsubquestion($input, $record) {

        $child = self::find($input['child_id']);

        $child->update([
            'parent_id' => $input['parent_id'],
            'activate_on_answer_id' => $input['activate_on_answer_id'],
        ]);

        self::fixTree();
        
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