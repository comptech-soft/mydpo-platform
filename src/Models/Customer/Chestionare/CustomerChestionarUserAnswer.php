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

    public static function attachAnswer($customer_chestionar_user_id, $input)
    {

        if(! array_key_exists('subanswers', $input) )
        {
            $input['subanswers'] = NULL;
        }

        $record = self::where('customer_chestionar_user_id', $customer_chestionar_user_id)->where('question_id', $question_id)->first();

        if( ! $record )
        {
            $record = self::create([
                'customer_chestionar_user_id' => $customer_chestionar_user_id,
                ...$input,
            ]);
        }
        else
        {
            $record->update([
                'customer_chestionar_user_id' => $customer_chestionar_user_id,
                ...$input,
            ]);
        }

        return $record;
    }

}