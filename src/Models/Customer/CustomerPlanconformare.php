<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
// use MyDpo\Performers\CustomerPlanconformare\GetNextNumber;
use MyDpo\Performers\CustomerPlanconformare\SaveRows;

class CustomerPlanconformare extends Model {

    use Itemable, Actionable;

    protected $table = 'customers-planuri-conformare';

    protected $casts = [
        'props' => 'json',
        'current_lines' => 'json',
        'columns' => 'json',
        'customer_id' => 'integer',
        'department_id' => 'integer',
        'visibility' => 'integer',
        'year' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'centralizator_id',
        'department_id',
        'visibility',
        'number',
        'year',
        'responsabil_nume',
        'responsabil_functie',
        'props',
        'current_lines',
        'columns',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = [
        'visible',
    ];

    protected $with = [
        'department',
        'rows.children'
    ];

    public function getVisibleAttribute() {
        return [
            'color' => !! $this->visibility ? 'green' : 'red',
            'icon' => !! $this->visibility ? 'mdi-check' : 'mdi-cancel',
        ];
    }

    public function department() {
        return $this->belongsTo(CustomerDepartment::class, 'department_id')->select(['id', 'departament']);
    }

    function rows() {
        return $this->hasMany(CustomerPlanconformareRow::class, 'customer_plan_id')->whereIn('type', ['capitol', 'actiune', 'subactiune'])->orderBy('order_no');
    }

    public static function doInsert($input, $record) { 
        $current_lines = PlanConformare::whereNull('parent_id')->get()->toArray();

        $input = [
            ...$input,
            'current_lines' => $current_lines,
            'columns' => PlanConformare::GetColumns(),
        ];

        $record = self::create($input);

        $record->CreateRows();

        return self::find($record->id);
    }

    public static function doDuplicate($input, $record) {

       

    }

    public static function doDelete($input, $record) {
        $record->DeleteRows();
        $record->delete();
        return $record;
    }

    public function DeleteRows() {
        $rows = CustomerPlanconformareRow::where('customer_plan_id', $this->id)->get();
        foreach($rows as $i => $row)
        {
            $row->delete();
        }
    }

    public static function doGetnextnumber($input) {

        dd($input);
        // return (new GetNextNumber($input))->Perform();
    }

    public static function saveRows($input) {
        return (new SaveRows($input))->Perform();
    }

    public function CreateRows() {

        foreach(Planconformare::all() as $i => $row) 
        {
            $input = [
                'customer_plan_id' => $this->id,
                'customer_id' => $this->customer_id,
                'plan_id' => $row->id,
                'pondere' => $row->procent_pondere,
                ...collect($row->toArray())
                    ->except(['created_at', 'updated_at', 'created_by', 'updated_by', 'children', 'id', 'pondere', 'procent_pondere'])
                    ->toArray(),
                
            ];

            CustomerPlanconformareRow::create($input);
        }
    }

}