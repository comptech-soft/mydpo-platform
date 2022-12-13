<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\User;
use MyDpo\Models\Curs;
use MyDpo\Models\Sharematerial;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Performers\CustomerCursUser\GetCounter;

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
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = [
        'my_status'
    ];

    protected $with = [
        'user',
        'curs',
        'trimitere'
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
        }
        return [
            'status' => $status,
            'color' => $color,
        ];
    }


    public function user() {
        return $this->belongsTo(User::class, 'user_id');
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
}