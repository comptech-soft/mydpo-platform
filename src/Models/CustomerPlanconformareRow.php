<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
// use Kalnoy\Nestedset\NodeTrait;
// use MyDpo\Traits\Itemable;
// use MyDpo\Traits\Actionable;
// use MyDpo\Performers\CustomerPlanconformare\GetNextNumber;
// use MyDpo\Performers\CustomerCentralizator\Export;
// use MyDpo\Performers\CustomerCentralizator\Import;
// use MyDpo\Performers\CustomerCentralizator\SaveSettings;
// use MyDpo\Performers\CustomerCentralizator\SetAccess;

class CustomerPlanconformareRow extends Model {

    // use NodeTrait;

    protected $table = 'customers-planuri-conformare-rows';

    protected $casts = [
        'props' => 'json',

        'customer_id' => 'integer',
        'customer_plan_id' => 'integer',
        'plan_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',

        'pondere' => 'decimal:2',
        'value' => 'decimal:2',
       
    ];

    protected $fillable = [
        'id',
        'customer_plan_id',
        'customer_id',
        'plan_id',
        'actiune',
        'order_no',
        'type',
        'frecventa',
        'responsabil',
        'pondere',
        'parent_id',
        'value',
        'props',
        'created_by',
        'updated_by',
    ];

    public function children() {
        return $this->hasMany(CustomerPlanconformareRow::class, 'parent_id', 'plan_id');
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

}