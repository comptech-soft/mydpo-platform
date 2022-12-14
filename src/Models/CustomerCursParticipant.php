<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Performers\CustomerCursParticipant\ImportParticipants;

class CustomerCursParticipant extends Model {

    protected $table = 'customers-cursuri-participants';

    protected $casts = [
        'props' => 'json',
        'customer_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'customer_curs_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_curs_id',
        'customer_id',
        'platform',
        'last_name',
        'first_name',
        'functiie',
        'data',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function importParticipants($input) {
        return (new ImportParticipants($input))->Perform();
    }
}