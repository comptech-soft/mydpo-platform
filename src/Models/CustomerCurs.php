<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Curs;
use MyDpo\Models\CustomerCursUser;
use MyDpo\Performers\CustomerCurs\GetSummary;

class CustomerCurs extends Model {

    protected $table = 'customers-cursuri';

    protected $casts = [
        'props' => 'json',
        'customer_id' => 'integer',
        'curs_id' => 'integer',
        'trimitere_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'effective_time' => 'float',
        'assigned_users' => 'json',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'curs_id',
        'trimitere_id',
        'status',
        'effective_time',
        'assigned_users',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $with = [
        'curs',
        'cursusers',
    ];

    public function curs() {
        return $this->belongsTo(Curs::class, 'curs_id');
    }

    public function cursusers() {
        return $this->hasMany(CustomerCursUser::class, 'customer_curs_id');
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function getSummary($input) {
        return (new GetSummary($input))->Perform();
    }
}