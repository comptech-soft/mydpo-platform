<?php

namespace MyDpo\Models\Customer\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Authentication\User;
use MyDpo\Models\Livrabile\Chestionare\Chestionar;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Sharematerial;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use Carbon\Carbon;

class CustomerChestionarUser extends Model {

    use Itemable, Actionable;

    protected $table = 'customers-chestionare-users';

    protected $casts = [
        'props' => 'json',
        'customer_id' => 'integer',
        'chestionar_id' => 'integer',
        'trimitere_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'user_id' => 'integer',
        'customer_chestionar_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_chestionar_id',
        'customer_id',
        'chestionar_id',
        'trimitere_id',
        'user_id',
        'status',
        'platform',
        'received_at',
        'started_at',
        'finished_at',

        'props',
        
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = [
        // 'my_status',
        // 'status_termen',
        // 'finalizat',
    ];

    protected $with = [
        // 'user',
        // 'trimitere',
        // 'createdby',
    ];

    // protected $statuses = [

    //     'sended' => [
    //         'text' => 'Neînceput',
    //         'color' => 'red',
    //     ],

    //     'done' => [
    //         'text' => 'Finalizat',
    //         'color' => 'green',
    //     ],

    //     'started' => [
    //         'text' => 'Început',
    //         'color' => 'orange',
    //     ]
    // ];

    // public function getMyStatusAttribute() {
    //     return array_key_exists($this->status, $this->statuses) 
    //         ? $this->statuses[$this->status]
    //         : [
    //             'text' => '-',
    //             'color' => 'grey',
    //         ];
    // }

    // public function getFinalizatAttribute() {
    //     if($this->status != 'done' || ! $this->done_at || ! $this->trimitere->date_to)
    //     {
    //         return NULL;
    //     }

    //     $now = Carbon::createFromFormat('Y-m-d H:i:s', $this->done_at);
    //     $expire = Carbon::createFromFormat('Y-m-d', $this->trimitere->date_to);

    //     $daysDiff = $expire->diffInDays($now, false);
    //     $hoursDiff = $expire->diffInHours($now, false);

    //     return $daysDiff <= 0 
    //         ? [
    //             'text' => 'Da',
    //             'color' => 'green'
    //         ]
    //         : [
    //             'text' => 'Nu',
    //             'color' => 'red'
    //         ];
    // }

    // public function getStatusTermenAttribute() {

    //     $daysDiff = $hoursDiff = NULL;

    //     $color = NULL;
    //     $text = NULL;

    //     if($this->status == 'done')
    //     {

    //     }
    //     else
    //     {
    //         $color = 'green';
    //         if( $this->trimitere->date_to )
    //         {
    //             $now = Carbon::now();
    //             $expire = Carbon::createFromFormat('Y-m-d', $this->trimitere->date_to);
        
    //             $daysDiff = $expire->diffInDays($now, false);
    //             $hoursDiff = $expire->diffInHours($now, false);

                
    //             if( ($daysDiff > 0) || ( ($daysDiff == 0) && ($hoursDiff > 0)))
    //             {
    //                 $color = 'red';
    //                 $text = 'Depășit';
    //             }

    //             else
    //             {
    //                 if(-5 <= $daysDiff && $daysDiff <= 0 )
    //                 {
    //                     $color = 'orange';
    //                     $text = 'În curând';
    //                 }
    //                 else
    //                 {
    //                     $color = 'blue';
    //                     $text = 'Asignate';
    //                 }
    //             }
                
    //         }
    //     }

    //     return [
    //         'date_from' => $this->trimitere->date_from,
    //         'date_to' => $this->trimitere->date_to,
    //         'days' => $daysDiff,
    //         'hours' => $hoursDiff,
    //         'color' => $color,
    //         'caption' => $text,
    //     ];
    // }

    // public function user() {
    //     return $this->belongsTo(User::class, 'user_id')->select(['id', 'first_name', 'last_name']);
    // }

    // public function createdby() {
    //     return $this->belongsTo(User::class, 'created_by');
    // }

    public function chestionar() {
        return $this->belongsTo(Chestionar::class, 'chestionar_id');
    }

    // public function trimitere() {
    //     return $this->belongsTo(Sharematerial::class, 'trimitere_id')->select(['id', 'number', 'date', 'date_from', 'date_to', 'sender_full_name']);
    // }

    // public function customer() {
    //     return $this->belongsTo(Customer::class, 'customer_id');
    // }

    // public function removeRecord() {
    //     $record = SharematerialDetail::where('trimitere_id', $this->trimitere_id)
    //         ->where('customer_id', $this->customer_id)
    //         ->where('assigned_to', $this->user_id)
    //         ->where('sended_document_id', $this->curs_id)
    //         ->first();

    //     $record->delete();

    //     $this->delete();
    // }


    // public static function getCounter($input) {
    //     return (new GetCounter($input))->Perform();
    // }

    // public static function changeStatus($input) {
    //     return (new ChangeStatus($input))->Perform();
    // }

    // public static function assignCursuri($input) {
    //     return (new AssignCursuri($input))->Perform();
    // }

    // public static function doChangestatus($input, $record) {

    //     $now = \Carbon\Carbon::now();

    //     $record->status = $input['status'];

    //     if($record->status == 'done')
    //     {
    //         $record->done_at = $now;
    //     }
    //     else
    //     {
    //         $record->done_at = NULL;
    //     }

    //     $props = !! $record->props ? $record->props : [];
    //     $props[$record->status . '_at'] = $now;
    //     $record->props = $props;

    //     $record->save();

    //     return $record;
    // }

    // public static function doSetstatus($input, $record) {
    //     return self::doChangestatus($input, $record);
    // }

    // public static function doDezasociere($input, $record) {
    //     /**
    //      * Se sterg inregistraile din tabela [customers-cursuri-users]
    //      */
    //     $records = self::where('customer_id', $input['customer_id'])
    //         ->where('customer_curs_id', $input['customer_curs_id'])
    //         ->whereIn('user_id', $input['users'])
    //         ->delete();

    //     CustomerCurs::Sync($input['customer_id']);

    //     return $records;
    // }

    // public static function doDelete($input, $record) {

    //     $record->delete();

    //     CustomerCurs::Sync($input['customer_id']);

    //     return $record;
    // }

    // public static function GetQuery() {
    //     return 
    //         self::query()
    //         ->leftJoin(
    //             'users',
    //             function($q) {
    //                 $q->on('users.id', '=', 'customers-cursuri-users.user_id');
    //             }
    //         )
    //         ->whereRaw("((`customers-cursuri-users`.`deleted` IS NULL) OR (`customers-cursuri-users`.`deleted` = 0))")
    //         ->select('customers-cursuri-users.*');
    // }

    public static function AttachUser($input) {

        $input = [
            ...$input,
            'created_by' => \Auth::user()->id,
        ];

        $record = self::where('customer_id', $input['customer_id'])
            ->where('chestionar_id', $input['chestionar_id'])
            ->where('user_id', $input['user_id'])
            ->first();

        if($record)
        {
            $record->update($input);
        }
        else
        {
            $record = self::create($input);
        }

        $record->refresh();

        event(
            new \MyDpo\Events\Customer\Livrabile\Chestionare\Trimitere(
                'chestionar.trimitere', 
                [
                    'nume_chestionar' => $record->chestionar->name,
                    'customers' => [$input['customer_id'] . '#' . $input['user_id']],
                    'link' => '/customer-user-chestionar/' . $input['user_id'] . '/' . $input['customer_id'],
                ]
            )
        );

    }
}