<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\User;
use MyDpo\Models\Curs;
use MyDpo\Models\Sharematerial;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Performers\CustomerCursUser\GetCounter;
use MyDpo\Performers\CustomerCursUser\ChangeStatus;
use MyDpo\Performers\CustomerCursUser\AssignCursuri;
use Carbon\Carbon;

class CustomerCursUser extends Model {

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
        'curs',
        'trimitere',
        'createdby',
    ];

    public function getMyStatusAttribute() {
        $status = $color = '';
        if($this->status == 'sended')
        {
            $status = 'Neînceput';
            $color = 'red';
        }
        else
        {
            if($this->status == 'done')
            {
                $status = 'Finalizat';
                $color = 'green';
            }
            else
            {
                if($this->status == 'started')
                {
                    $status = 'Început';
                    $color = 'orange';
                }
            }
        }
        return [
            'status' => $status,
            'color' => $color,
        ];
    }

    public function getStatusTermenAttribute() {

        $daysDiff = $hoursDiff = NULL;

        $color = NULL;
        $text = NULL;

        if($this->status == 'done')
        {

        }
        else
        {
            $color = 'green';
            if( $this->trimitere->date_to )
            {
                $now = Carbon::now();
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
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdby() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function curs() {
        return $this->belongsTo(Curs::class, 'curs_id');
    }

    public function trimitere() {
        return $this->belongsTo(Sharematerial::class, 'trimitere_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function getCounter($input) {
        return (new GetCounter($input))->Perform();
    }

    public static function changeStatus($input) {
        return (new ChangeStatus($input))->Perform();
    }

    public static function assignCursuri($input) {
        return (new AssignCursuri($input))->Perform();
    }
}