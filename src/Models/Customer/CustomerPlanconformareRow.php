<?php

namespace MyDpo\Models\Customer;

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
        'created_by' => 'integer',
        'updated_by' => 'integer',

        'pondere' => 'decimal:2',
        'value_inceput_an' => 'decimal:2',
        'value_final_s1' => 'decimal:2',
        'value_final_s2' => 'decimal:2',
        'realizat_inceput_an' => 'decimal:2',
        'realizat_final_s1' => 'decimal:2',
        'realizat_final_s2' => 'decimal:2',
       
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
        'value_inceput_an', 
        'value_final_s1', 
        'value_final_s2', 
        'realizat_inceput_an',
        'realizat_final_s1',
        'realizat_final_s2',
        'observatii',
        'parent_id',
        'value',
        'props',
        'created_by',
        'updated_by',
    ];

    public function mychildren() {
        return $this->hasMany(CustomerPlanconformareRow::class, 'parent_id', 'plan_id');
    }

    public function children()
    {
        return $this->mychildren()->orderBy('order_no')->with('children');
    }

    public static function AddRows($customer_plan_id, $parent_id, &$r, $level) {

        $rows = self::where('customer_plan_id', $customer_plan_id)->orderBy('order_no')->with([
            
            'children' => function($q) use ($customer_plan_id){

                $q->where('customer_plan_id', $customer_plan_id);
            },
        ]);

        if(! $parent_id )
        {
            $rows = $rows->whereNull('parent_id')->get();
        }
        else
        {
            $rows = $rows->where('parent_id', $parent_id)->get();
        }

        foreach($rows as $i => $row)
        {
            
            $r[] = [
                ...$row->toArray(),
                'level' => $level,
            ];

            if($level < 3)
            {
                self::AddRows($customer_plan_id, $row->plan_id, $r, $level + 1);
            }
            
        }
    }

    public static function CalculateTree($customer_plan_id) {

        self::CalculateTreeByType($customer_plan_id, 'modalitate');

        self::CalculateTreeByType($customer_plan_id, 'subactiune');

        self::CalculateTreeByType($customer_plan_id, 'actiune');

        self::CalculateTreeByType($customer_plan_id, 'capitol');

    }

    public static function CalculateTreeByType($customer_plan_id, $type) {

        $records = self::whereType($type)->where('customer_plan_id', $customer_plan_id)->orderBy('order_no')->get();

        foreach($records as $i => $record)
        {
            if($type == 'modalitate')
            {
                $record->CalculateLeaf();
            }
            else
            {
                $record->CalculateNode();
            }
            
        }

    }

    protected function CalculateLeaf() {

        $this->realizat_inceput_an = round($this->pondere * $this->value_inceput_an / 100, 2);
        $this->realizat_final_s1 = round($this->pondere * $this->value_final_s1 / 100, 2);
        $this->realizat_final_s2 = round($this->pondere * $this->value_final_s2 / 100, 2);

        $this->save();
    }

    protected function CalculateNode() {

        $sum_inceput_an = $sum_final_s1 = $sum_final_s2 = 0;

        foreach($this->children as $i => $child)
        {
            $sum_inceput_an += ($child->realizat_inceput_an);
            $sum_final_s1 += ($child->realizat_final_s1);
            $sum_final_s2 += ($child->realizat_final_s2);
        }

        $this->realizat_inceput_an = $sum_inceput_an;
        $this->realizat_final_s1 = $sum_final_s1;
        $this->realizat_final_s2 = $sum_final_s2;

        $this->value_inceput_an = $this->realizat_inceput_an * 100/$this->pondere;
        $this->value_final_s1 = $this->realizat_final_s1 * 100/$this->pondere;
        $this->value_final_s2 = $this->realizat_final_s2 * 100/$this->pondere;

        $this->save();
    }

}