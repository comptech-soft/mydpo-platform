<?php

namespace MyDpo\Models\Customer\Planuriconformare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Exportable;
use MyDpo\Models\Livrabile\Planuriconformare\Planconformare as Structura;
use MyDpo\Models\Customer\Departments\Department;
use MyDpo\Exports\Customer\Livrabile\Planconformare\Exporter;
use MyDpo\Models\Authentication\User;

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
        return $this->belongsTo(Department::class, 'department_id')->select(['id', 'departament']);
    }

    function rows() {
        return $this->hasMany(PlanconformareRow::class, 'customer_plan_id')->whereIn('type', ['capitol', 'actiune', 'subactiune'])->orderBy('order_no');
    }

    protected static function GetExporter($input) {
        return new Exporter($input); 
    }

    public static function doInsert($input, $record) { 
        $record = self::create([
            ...$input,
            'current_lines' => Structura::whereNull('parent_id')->get()->toArray(),
            'columns' => Structura::GetColumns(),
            'pondere_total' => Structura::TotalPondere(),
            'value_inceput_an' => 0,
            'value_final_s1' => 0,
            'value_final_s2' => 0,
            'realizat_inceput_an' => 0,
            'realizat_final_s1' => 0,
            'realizat_final_s2' => 0,
        ]);

        $record->CreateRows();

        if($record->visibility == 1)
        {
            $receivers = self::GetReceivers($record->customer_id);

            event(new \MyDpo\Events\Customer\Livrabile\Planuriconformare\Visibility('planconformare.visibility', [
                'numar' => $record->number,
                'year' => $record->year,
                'customers' => $receivers, 
                'link' => '/customer-plan-conformare-details/' . $record->customer_id . '/' . $record->id,            
            ]));
        }
        
        return self::find($record->id);
    }

    public static function doUpdate($input, $record) {

        $record->update($input);

        if($record->visibility == 1)
        {
            $receivers = self::GetReceivers($record->customer_id);

            event(new \MyDpo\Events\Customer\Livrabile\Planuriconformare\Visibility('planconformare.visibility', [
                'numar' => $record->number,
                'year' => $record->year,
                'customers' => $receivers, 
                'link' => '/customer-plan-conformare-details/' . $record->customer_id . '/' . $record->id,            
            ]));
        }
        
        return self::find($record->id);
    }

    public static function GetReceivers($customer_id) {

        $sql = "
            SELECT 
                user_id 
            FROM `customers-persons` 
            WHERE 
                (customer_id = " . $customer_id . ") AND 
                (user_id <> " . \Auth::user()->id . ")
        ";

        $accounts = \DB::select($sql);
       
        $sql = "
            SELECT
                id as user_id
            FROM `users`
            WHERE
                (type = 'admin') AND 
                (id <> " . \Auth::user()->id . ")
        ";
        
        $admins = \DB::select($sql);

        $receivers = [];
        
        foreach([...$accounts, ...$admins] as $i => $item)
        {
            if(! in_array($item->user_id, $receivers) )
            {
                $receivers[] = $item->user_id;
            }
        }

        $users = User::whereIn('id', $receivers)->whereRaw('( deleted is NULL || (deleted = 0))')->get()->map(function($user){
            return $user->id;
        })->toArray();

        return collect($users)->map(function($item) use ($customer_id) {
            return $customer_id . '#' . $item;
        })->toArray();

    }
    
    public static function doDuplicate($input, $record) {
    }

    public static function doDelete($input, $record) {
        $record->DeleteRows();
        $record->delete();
        return $record;
    }

    public function DeleteRows() {
        $rows = PlanconformareRow::where('customer_plan_id', $this->id)->get();
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
                $row = PlanconformareRow::find($data['id']);
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
                $row = PlanconformareRow::find($id);

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
            
            PlanconformareRow::create($input);
        }
    }

    public function GetRowsAsTable() {
        $r = [];
        PlanconformareRow::AddRows($this->id, NULL, $r, 1);
        return $r;
    }

    public function GetTree() {
        $nodes = PlanconformareRow::where('customer_plan_id', $this->id)
            ->whereNull('parent_id')
            ->orderBy('order_no')
            ->with(['children'])
            ->get();
        return $nodes->toArray();
    }

    /**
     * Actualizeaza planul
     */
    public function UpdateSummary() {
        $nodes = PlanconformareRow::where('customer_plan_id', $this->id)->whereNull('parent_id')->get();

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
        PlanconformareRow::CalculateTree($this->id);
        $this->UpdateSummary();
    }

    public static function GetQuery() {
        return config('app.platform') == 'admin' ? self::query() : self::query()->whereVisibility(1);  
    }

}