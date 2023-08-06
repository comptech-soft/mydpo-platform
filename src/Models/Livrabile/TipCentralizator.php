<?php

namespace MyDpo\Models\Livrabile;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Centralizatorable;
use MyDpo\Scopes\NotdeletedScope;
use MyDpo\Performers\Customer\Centralizatoare\Dashboard\SaveCustomerAsociere;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizator;

class TipCentralizator extends Model {

    use Itemable, Actionable, Centralizatorable;

    protected $table = 'centralizatoare';
    
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'deleted' => 'integer',
        'props' => 'json',

        'on_gap_page' => 'integer',
        'on_centralizatoare_page' => 'integer',

        'has_nr_crt_column' => 'integer',
        'has_visibility_column' => 'integer',
        'has_status_column' => 'integer',
        'has_files_column' => 'integer',
        'has_department_column' => 'integer',

        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        // 'status',
        'name',
        'category_id',
        'description',
        // 'subject',
        // 'body',
        'on_gap_page',
        'on_centralizatoare_page',

        'has_nr_crt_column',
        'has_visibility_column',
        'has_status_column',
        'has_files_column',
        'has_department_column',

        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $with = [
        'category'
    ];

    protected $appends = [
        'bool_col_nrcrt',
        'bool_col_visibility',
        'bool_col_status',
        'bool_col_files',
        'bool_col_department'
    ];

    protected $columnsDefinition = [
        'model' => \MyDpo\Models\Livrabile\TipCentralizatorColoana::class,
        'foreign_key' => 'centralizator_id',
    ];

    protected static function booted() {
        static::addGlobalScope(new NotdeletedScope());
    }

    public function getBoolColNrcrtAttribute() {
        return !! $this->has_nr_crt_column ? 1 : 0;
    }

    public function getBoolColVisibilityAttribute() {
        return !! $this->has_visibility_column ? 1 : 0;
    }

    public function getBoolColStatusAttribute() {
        return !! $this->has_status_column ? 1 : 0;
    }

    public function getBoolColFilesAttribute() {
        return !! $this->has_files_column ? 1 : 0;
    }

    public function getBoolColDepartmentAttribute() {
        return !! $this->has_department_column ? 1 : 0;
    }

    public function getColumnsTreeAttribute() {
        return __METHOD__;
    }
    
    public function getColumnsItemsAttribute() {
        return __METHOD__;
    }
    
    public function category() {
        return $this->belongsTo(Category::class, 'category_id')->select(['id', 'name']);
    }

    public function columns() {
        return $this->hasMany(TipCentralizatorColoana::class, 'centralizator_id');
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

                'centralizatoare.on_gap_page', 
                'centralizatoare.on_centralizatoare_page',

                'centralizatoare.has_nr_crt_column',
                'centralizatoare.has_visibility_column',
                'centralizatoare.has_status_column',
                'centralizatoare.has_files_column',
                'centralizatoare.has_department_column',

                'centralizatoare.props'
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
            'centralizatoare.on_centralizatoare_page',
            'centralizatoare.on_gap_page',
            'is_associated'
        ]);

        $records = $q->get()->filter(function($item) use ($input){

            if($input['centralizatoare'] == 1 && $item->on_centralizatoare_page == 1)
            {
                return TRUE;
            }

            if($input['gap'] == 1 && $item->on_gap_page == 1)
            {
                return TRUE;
            }

            return FALSE;
        });

        if(config('app.platform') == 'b2b')
        {
            $records = $records->filter( function($item) {

                return !! $item->is_associated;
            });
        }

        $result = $records->toArray(); 

        foreach($result as $i => $item)
        {
            $result[$i]['count'] = CustomerCentralizator::where('customer_id', $customer_id)->where('centralizator_id', $item['id'])->count();
        }

        return $result;
    }

    public static function GetRules($action, $input) {
        
        if( ! in_array($action, ['insert', 'update']) )
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

            'description' => [
                'required',
            ],
           
        ];
        
        return $result;
    }

}