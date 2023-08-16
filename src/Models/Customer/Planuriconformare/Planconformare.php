<?php

namespace MyDpo\Models\Customer\Planuriconformare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Models\Livrabile\Planconformare as Structura;
use MyDpo\Traits\Exportable;
use MyDpo\Exports\CustomerPlanconformare\Exporter;

class Planconformare extends Model {

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

        $current_lines = Structura::whereNull('parent_id')->get()->toArray();

        $input = [
            ...$input,
            'current_lines' => $current_lines,
            'columns' => Structura::GetColumns(),
            'pondere_total' => Structura::TotalPondere(),
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

        $record = self::find($input['plan_id']);

        $record->CalculateTree();

        return [
            'rows' => $record->GetRowsAsTable(),
            'plan' => $record,
            'tree' => $record->GetTree(),
        ];
    }

    public static function doUpdaterows($input, $record) {

        if(array_key_exists('nodes', $input))
        {
            foreach($input['nodes'] as $id => $node)
            {
                $row = CustomerPlanconformareRow::find($id);

                $row->value_inceput_an = $node['value_inceput_an'];
                $row->value_final_s1 = $node['value_final_s1'];
                $row->value_final_s2 = $node['value_final_s2'];

                $row->save();
            }
        }

        $record = self::find($input['plan_id']);

        $record->CalculateTree();

        return [
            'rows' => $record->GetRowsAsTable(),
            'plan' => $record,
        ];
    }

    public static function doRefresh($input, $record) {
        $record = self::find($input['plan_id']);

        $record->CalculateTree();

        return $record;
    }

    public function CreateRows() {

        foreach(Structura::all() as $i => $row) 
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

    public function GetTree() {
        $nodes = CustomerPlanconformareRow::where('customer_plan_id', $this->id)->whereNull('parent_id')->with(['children'])->get();
        return $nodes->toArray();
    }

    /**
     * Actualizeaza planul
     */
    public function UpdateSummary() {
        $nodes = CustomerPlanconformareRow::where('customer_plan_id', $this->id)->whereNull('parent_id')->get();

        $sum_inceput_an = $sum_final_s1 = $sum_final_s2 = 0;

        foreach($nodes as $i => $node)
        {
            $sum_inceput_an += round($node->realizat_inceput_an, 2);
            $sum_final_s1 += round($node->realizat_final_s1, 2);
            $sum_final_s2 += round($node->realizat_final_s2, 2);
            
        }

        $this->realizat_inceput_an = $sum_inceput_an;
        $this->realizat_final_s1 = $sum_final_s1;
        $this->realizat_final_s2 = $sum_final_s2;

        $this->value_inceput_an = round($this->realizat_inceput_an * 100/$this->pondere_total, 2);
        $this->value_final_s1 = round($this->realizat_final_s1 * 100/$this->pondere_total, 2);
        $this->value_final_s2 = round($this->realizat_final_s2 * 100/$this->pondere_total, 2);

        $this->save();
    }

    /**
     * Calculeaza valorile pentru toate randurile
     * Actualizeaza planul
     */

    public function CalculateTree() {
        CustomerPlanconformareRow::CalculateTree($this->id);
        $this->UpdateSummary();
    }

    public static function CountLivrabile($customer_id) {

        return $customer_id;
    } 

}