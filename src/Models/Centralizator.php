<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Models\Category;
use MyDpo\Scopes\NotdeletedScope;

class Centralizator extends Model {

    use Itemable, Actionable;

    protected $table = 'centralizatoare';
    
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'deleted' => 'integer',
        'body' => 'json',
        'status' => 'json',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'status',
        'name',
        'category_id',
        'description',
        'subject',
        'body',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $with = [
        'category'
    ];

    protected static function booted() {
        static::addGlobalScope( new NotdeletedScope() );
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id')->select(['id', 'name']);
    }

    public static function GetQuery() {
        return 
            self::query()
            ->leftJoin(
                'categories',
                function($j) {
                    $j->on('categories.id', '=', 'centralizatoare.category_id');
                }
            )
            ->select([
                'centralizatoare.id', 
                'centralizatoare.name', 
                'centralizatoare.category_id', 
                'centralizatoare.description', 
                'centralizatoare.body', 
                'centralizatoare.status'
            ]);
        ;
    }

    public static function getCustomerAsociere($input) {

        $customer_id = $input['customer_id'];

        $q = self::query()->leftJoin(

            'customers-centralizatoare-asociere',
            
            function($j) use ($customer_id){
                $j
                    ->on('customers-centralizatoare-asociere.centralizator_id', '=', 'centralizatoare.id')
                    ->where('customers-centralizatoare-asociere.customer_id', $customer_id);
            }

        )->select([
            'centralizatoare.id',
            'centralizatoare.name',
            'centralizatoare.category_id',
            'centralizatoare.description',
            'centralizatoare.status',
            'centralizatoare.body',
            'is_associated'
        ]);

        $records = $q->get();

        return $records;
        
    }

    public static function doInsert($input, $record) {

        $record = self::create($input);

        foreach($input['body'] as $key => $value)
        {
            if($value == 1)
            {
                $record->{'AddColumn' . ucfirst($key)}();
            }
        }

        return $record;
    }

    public static function doUpdate($input, $record) {

        $record->update($input);

        foreach($input['body'] as $key => $value)
        {
            if($value == 1)
            {
                $record->{'AddColumn' . ucfirst($key)}();
            }
            else
            {
                $record->{'DeleteColumn' . ucfirst($key)}();
            }
        }

        return $record;
    }

    public function DeleteColumnStatus() {
        $this->DeleteColumn('STATUS');
    }

    public function DeleteColumnDepartament() {
        $this->DeleteColumn('DEPARTMENT');
    }

    public function DeleteColumnVizibilitate() {
        $this->DeleteColumn('VISIBILITY');
    }

    public function AddColumnVizibilitate() {
        $this->AddColumn('Vizibilitate', 'VISIBILITY', -100, 100);
    }

    public function AddColumnStatus() {
        $this->AddColumn('Status', 'STATUS', -90, 100);
    }

    public function AddColumnDepartament() {
        $this->AddColumn('Departament', 'DEPARTMENT', -80, 200);
    }

    public function DeleteColumn($type) {
        $record = CentralizatorColoana::where('centralizator_id', $this->id)->where('type', $type)->delete();
    }

    public function AddColumn($caption, $type, $order_no, $width) {
        
        $input = [
            'centralizator_id' => $this->id,
            'caption' => $caption,
            'slug' => md5($caption . time()),
            'is_group' => 0,
            'group_id' => NULL,
            'type' =>  $type,
            'order_no' => $order_no,
            'width' => $width,
        ];

        $record = CentralizatorColoana::where('centralizator_id', $this->id)->where('type', $type)->first();

        if(!! $record)
        {
            $record->update($input);
        }
        else
        {
            $record = CentralizatorColoana::create($input);
        }

    }

    public static function doDelete($input, $record) {

        CentralizatorColoana::where('centralizator_id', $record->id)->delete();

        $record->deleted = 1;
        $record->deleted_by = \Auth::user()->id;
        $record->save();

        return $record;

    }

    public static function GetRules($action, $input) {


        if($action == 'delete')
        {
            return NULL;
        }
        $result = [
            'name' => [
                'required',
                // new UniquePermission($input),
            ],
            'category_id' => [
                'required',
            ],
           
        ];
        return $result;
    }

}