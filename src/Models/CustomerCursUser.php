<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $fillable = [
        'id',
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

}