<?php

namespace MyDpo\Models\Customer\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Chestionar;
// use MyDpo\Models\CustomerChestionarUser;
use MyDpo\Performers\CustomerChestionar\GetSummary;

class CustomerChestionar extends Model {

    protected $table = 'customers-chestionare';

    protected $casts = [
        // 'props' => 'json',
        // 'customer_id' => 'integer',
        // 'curs_id' => 'integer',
        // 'trimitere_id' => 'integer',
        // 'created_by' => 'integer',
        // 'updated_by' => 'integer',
        // 'deleted_by' => 'integer',
        // 'deleted' => 'integer',
        // 'effective_time' => 'float',
        // 'assigned_users' => 'json',
    ];

    protected $fillable = [
        // 'id',
        // 'customer_id',
        // 'curs_id',
        // 'trimitere_id',
        // 'status',
        // 'effective_time',
        // 'assigned_users',
        // 'props',
        // 'deleted',
        // 'created_by',
        // 'updated_by',
        // 'deleted_by'
    ];

    protected $with = [
        'chestionar',
        // 'cursusers',
    ];

    public function chestionar() {
        return $this->belongsTo(Curs::class, 'chestionar_id');
    }

    // public function cursusers() {
    //     return $this->hasMany(CustomerCursUser::class, 'customer_curs_id');
    // }

    // public static function getItems($input) {
    //     return (new GetItems(
    //         $input, 
    //         self::query()->has('chestionar'), 
    //         __CLASS__
    //     ))->Perform();
    // }

    // public static function getSummary($input) {
    //     return (new GetSummary($input))->Perform();
    // }

    public static function CreateRecordsByTrimitere($trimitere) 
    {

        dd( __METHOD__, $trimitere->toArray() );

        // $numberOfitems = $trimitere->count_users * $trimitere->count_materiale;
        // $calculated_time = ($numberOfitems > 0) ? $trimitere->effective_time/$numberOfitems : 0; 

        // foreach($trimitere->customers as $customer_id => $users)
        // {
        //     foreach($trimitere->materiale_trimise as $i => $curs_id)
        //     {
        //         $input = [
        //             'customer_id' => $customer_id,
        //             'curs_id' => $curs_id,
        //             'trimitere_id' => $trimitere->id,
        //             'platform'=> config('app.platform'),
        //             'effective_time' => $calculated_time,
        //             'assigned_users' => $users,
        //             'trimitere_number' => $trimitere->number,
        //             'trimitere_date' => $trimitere->date,
        //             'trimitere_sended_by' => $trimitere->sender_full_name,
        //         ];

        //         self::CreateOrUpdateRecord($input);
        //     }

        //     self::Sync($customer_id);
        // }
    }
}