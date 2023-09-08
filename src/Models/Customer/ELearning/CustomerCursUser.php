<?php

namespace MyDpo\Models\Customer\ELearning;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Authentication\User;
// use MyDpo\Models\Curs;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Sharematerial;
// use MyDpo\Models\SharematerialDetail;
// use MyDpo\Models\Customer\Customer;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

// use MyDpo\Performers\CustomerCursUser\GetCounter;
// use MyDpo\Performers\CustomerCursUser\ChangeStatus;
// use MyDpo\Performers\CustomerCursUser\AssignCursuri;
use Carbon\Carbon;


class CustomerCursUser extends Model {

    use Itemable, Actionable;

    protected $table = 'customers-cursuri-users';

    protected $casts = [
        'props' => 'json',
        'customer_id' => 'integer',
        'curs_id' => 'integer',
        'trimitere_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'user_id' => 'integer',
        'customer_curs_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_curs_id',
        'customer_id',
        'curs_id',
        'trimitere_id',
        'user_id',
        'status',
        'done_at',
        'props',
        'platform',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = [
        'my_status',
        'status_termen',
    ];

    protected $with = [
        'user',
        // 'curs',
        'trimitere',
        // 'createdby',
    ];

    protected $statuses = [

        'sended' => [
            'text' => 'Neînceput',
            'color' => 'red',
        ],

        'done' => [
            'text' => 'Finalizat',
            'color' => 'green',
        ],

        'started' => [
            'text' => 'Început',
            'color' => 'orange',
        ]
    ];

    public function getMyStatusAttribute() {
        return array_key_exists($this->status, $this->statuses) 
            ? $this->statuses[$this->status]
            : [
                'text' => '-',
                'color' => 'grey',
            ];
    }

    public function getStatusTermenAttribute() {

        if($this->status != 'done' || ! $this->done_at)
        {
            return NULL;
        }

        $daysDiff = $hoursDiff = NULL;

        $color = 'green';
        $text = 'Finalizat';

        if( !! $this->trimitere->date_to)
        {
            $now = Carbon::createFromFormat('Y-m-d', $this->done_at);
            $expire = Carbon::createFromFormat('Y-m-d', $this->trimitere->date_to);
    
            $daysDiff = $expire->diffInDays($now, false);
            $hoursDiff = $expire->diffInHours($now, false);
            
            if( ($daysDiff > 0) || ( ($daysDiff == 0) && ($hoursDiff > 0)))
            {
                $color = 'red';
                $text = 'Depășit';
            }
            else
            {
                if(-5 <= $daysDiff && $daysDiff <= 0 )
                {
                    $color = 'orange';
                    $text = 'În curând';
                }
                else
                {
                    $color = 'blue';
                    $text = 'Asignate';
                }
            }
        }
        
        return [
            'date_from' => $this->trimitere->date_from,
            'date_to' => $this->trimitere->date_to,
            'days' => $daysDiff,
            'hours' => $hoursDiff,
            'color' => $color,
            'caption' => $text,
        ];
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id')->select(['id', 'first_name', 'last_name']);
    }

    // public function createdby() {
    //     return $this->belongsTo(User::class, 'created_by');
    // }

    // public function curs() {
    //     return $this->belongsTo(Curs::class, 'curs_id');
    // }

    public function trimitere() {
        return $this->belongsTo(Sharematerial::class, 'trimitere_id')->select(['id', 'number', 'date', 'date_from', 'date_to', 'sender_full_name']);
    }

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

    public static function doDezasociere($input, $record) {
        /**
         * Se sterg inregitraile din tabela [customers-cursuri-users]
         */
        $records = self::where('customer_id', $input['customer_id'])
            ->where('customer_curs_id', $input['customer_curs_id'])
            ->whereIn('user_id', $input['users'])
            ->delete();

        CustomerCurs::Sync($input['customer_id']);

        return $records;
    }

    public static function AttachUser($input) {

        $input = [
            ...$input,
            'created_by' => \Auth::user()->id,
        ];

        $record = CustomerCursUser::where('customer_id', $input['customer_id'])
            ->where('curs_id', $input['curs_id'])
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

    }
}