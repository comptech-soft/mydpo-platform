<?php

namespace MyDpo\Models\Livrabile;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Columnable;
use MyDpo\Scopes\NotdeletedScope;
use MyDpo\Performers\Centralizator\SaveCustomerAsociere;

class Centralizator extends Model {

    use Itemable, Actionable, Columnable;

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

    protected $columnsDefinition = [
        'model' => \MyDpo\Models\Livrabile\CentralizatorColoana::class,
        'foreign_key' => 'centralizator_id',
    ];

    protected static function booted() {
        static::addGlobalScope(new NotdeletedScope());
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id')->select(['id', 'name']);
    }

    public function columns() {
        return $this->hasMany(CentralizatorColoana::class, 'centralizator_id');
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
            ])
            ->withCount(['columns' => function($q) {
                $q->whereNull('group_id');
            }]);
    }

    public static function saveCustomerAsociere($input) {
        return (new SaveCustomerAsociere($input))->Perform();
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

        $records = $q->get()->filter(function($item) use ($input){

            if(! $item->status )
            {
                return false;
            }

            $visible = $input['gap'] * $item->status['gap'] + $input['centralizatoare'] * $item->status['centralizatoare'];

            return !! $item->status && $visible;

        });

        if(config('app.platform') == 'b2b')
        {
            $records = $records->filter( function($item) {

                return !! $item->is_associated;
            });
        }

        return $records->toArray();
    }

    public static function GetRules($action, $input) {
        
        if($action == 'delete')
        {
            return NULL;
        }

        $result = [
            'name' => [
                'required',
            ],
            'category_id' => [
                'required',
            ],
           
        ];
        return $result;
    }

}