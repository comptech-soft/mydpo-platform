<?php

namespace MyDpo\Models\Customer\Chestionare;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Models\Authentication\User;
// use MyDpo\Models\Livrabile\Chestionare\Chestionar;
// use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Sharematerial;
// use MyDpo\Traits\Itemable;
// use MyDpo\Traits\Actionable;
// use Carbon\Carbon;

class CustomerChestionarUserAnswer extends Model {


    protected $table = 'customers-chestionare-users-answers';

    protected $casts = [
        'subanswers' => 'json',
        'value' => 'json',
        'customer_chestionar_user_id' => 'integer',
        'question_id' => 'integer',
        'id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_chestionar_user_id',
        'question_id',
        'value',
        'subanswers'
    ];

    public static function IsCorrectAnswer($value, $question)
    {

        $response = $value['answer'];
        
        if( ! is_array($response) )
        {
            $response = [$response];
        }

        $response = collect($response)->map( function($item){ return 1 * $item; });

        $correct = collect($question['options'])->filter( function($item){

            return $item['is_correct'] == 1;

        })->map(function($item){ return 1 * $item['id']; });

       dd(collect($response)->diff($correct)->isEmpty());
    
    }

    public static function CalculateScore(array $question, int $customer_chestionar_user_id, int $customer_chestionar_id)
    {

        if( $question['parent_id'] != NULL)
        {
            return 0;
        }

        if(! $question['score'])
        {
            return 0;
        }

        $answer = self::where('customer_chestionar_user_id', $customer_chestionar_user_id)->where('question_id', $question['id'])->first();

        if( ! $answer )
        {
            return 0;
        }

        if( self::IsCorrectAnswer($answer->value, $question) )
        {
            return $question['score'];
        }

        return 0;
    }

    public static function CalculateQuestionsScore(int $customer_chestionar_user_id, int $customer_chestionar_id)
    {
        $chestionar = CustomerChestionar::find($customer_chestionar_id);
        
        // $records = self::where('customer_chestionar_user_id', $customer_chestionar_user_id)->get();

        $score = 0;

        foreach($chestionar->current_questions as $question)
        {
            $score += self::CalculateScore($question, $customer_chestionar_user_id, $customer_chestionar_id);
        }

        
        return $score;
    }

    public static function attachAnswer($customer_chestionar_user_id, $input)
    {

        if(! array_key_exists('subanswers', $input) )
        {
            $input['subanswers'] = NULL;
        }

        $record = self::where('customer_chestionar_user_id', $customer_chestionar_user_id)->where('question_id', $input['question_id'])->first();

        if( ! $record )
        {
            $record = self::create([
                ...$input,
                'customer_chestionar_user_id' => $customer_chestionar_user_id,
                'value' => [
                    'answer' => $input['value'],
                    'other' => $input['other']
                ],                
            ]);
        }
        else
        {
            $record->update([
                ...$input,
                'customer_chestionar_user_id' => $customer_chestionar_user_id,
                'value' => [
                    'answer' => $input['value'],
                    'other' => $input['other']
                ],   
            ]);
        }

        return $record;
    }

}