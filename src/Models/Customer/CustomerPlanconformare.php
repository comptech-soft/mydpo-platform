<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Models\Livrabile\PlanConformare;
use MyDpo\Traits\Exportable;
use MyDpo\Exports\CustomerPlanconformare\Exporter;

class CustomerPlanconformare extends Model {

    use Itemable, Actionable, Exportable;

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
        'pondere_total' => 'decimal:2',
        'value_inceput_an' => 'decimal:2',
        'value_final_s1' => 'decimal:2',
        'value_final_s2' => 'decimal:2',
        'realizat_inceput_an' => 'decimal:2',
        'realizat_final_s1' => 'decimal:2',
        'realizat_final_s2' => 'decimal:2',
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
        'pondere_total',
        'value_inceput_an',
        'value_final_s1',
        'value_final_s2',
        'realizat_inceput_an',
        'realizat_final_s1',
        'realizat_final_s2',
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

    protected static function GetExporter($input) {
        return new Exporter($input); 
    }

    public static function doInsert($input, $record) { 

        $current_lines = PlanConformare::whereNull('parent_id')->get()->toArray();

        $input = [
            ...$input,
            'current_lines' => $current_lines,
            'columns' => PlanConformare::GetColumns(),
            'pondere_total' => PlanConformare::TotalPondere(),
            'value_inceput_an' => 0,
            'value_final_s1' => 0,
            'value_final_s2' => 0,
            'realizat_inceput_an' => 0,
            'realizat_final_s1' => 0,
            'realizat_final_s2' => 0,
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

        $sql = "
            SELECT 
                MAX(CAST(`number` AS UNSIGNED)) as max_number 
            FROM `customers-planuri-conformare` 
            WHERE (customer_id=" . $input['customer_id'] . ")"
        ;

        $records = \DB::select($sql);

        return 1 + (count($records) > 0 ? $records[0]->max_number : 0);
    }

    public static function doSaverows($input) {
        $result = [];

        if($input['rows'] && is_array($input['rows']))
        {
       
            foreach($input['rows'] as $i => $data)
            {
                $row = CustomerPlanconformareRow::find($data['id']);
                $row->update($data);

                $result[] = $row;
            }
        }

        return self::find($input['plan_id'])->GetRowsAsTable();
    }

    public function CreateRows() {

        foreach(Planconformare::all() as $i => $row) 
        {
            $input = [
                'customer_plan_id' => $this->id,
                'customer_id' => $this->customer_id,
                'plan_id' => $this->id . '#' .  $row->id,
                'pondere' => $row->procent_pondere,
                ...collect($row->toArray())
                    ->except(['created_at', 'updated_at', 'created_by', 'updated_by', 'children', 'id', 'pondere', 'procent_pondere'])
                    ->toArray(),
                
                'parent_id' => (!! $row->parent_id ?  $this->id . '#' . $row->parent_id : NULL),
            ];
            
            CustomerPlanconformareRow::create($input);
        }
    }

    public function GetRowsAsTable() {

        $r = [];
        
        CustomerPlanconformareRow::AddRows($this->id, NULL, $r, 1);
        
        return $r;
    }

    public function CalculateTree() {
        CustomerPlanconformareRow::CalculateTree($this->id);
    }

}