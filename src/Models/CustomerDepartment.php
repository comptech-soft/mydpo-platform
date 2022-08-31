<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDepartment extends Model {

    protected $table = 'customers-departamente';

    protected $casts = [
        'props' => 'json',
        'customer_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'departament',
        'nume_responsabil',
        'numar_angajati',
        'telefon',
        'email',
        'deleted',
        'props',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

}