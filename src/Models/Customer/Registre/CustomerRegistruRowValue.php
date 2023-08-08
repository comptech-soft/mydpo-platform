<?php

namespace MyDpo\Models\Customer\Registre;

use Illuminate\Database\Eloquent\Model;

class CustomerRegistruRowValue extends Model {
   
    protected $table = 'customers-registers-rows-values';

    protected $casts = [
        'deleted' => 'integer',
        'row_id' => 'integer',
        'column_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'row_id',
        'column_id',
        'deleted',
        'value',
        'type',
        'created_by',
        'updated_by'
    ];

    public function row() {
        return $this->belongsTo(CustomerRegistruRow::class, 'row_id');
    }

    public function column() {
        return $this->belongsTo(RegistruColoana::class, 'column_id');
    }
    
}