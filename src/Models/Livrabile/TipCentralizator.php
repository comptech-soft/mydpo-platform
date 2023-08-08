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
        'name',
        'category_id',
        'description',
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

    public function category() {
        return $this->belongsTo(Category::class, 'category_id')->select(['id', 'name']);
    }

    public function columns() {
        return $this->hasMany(TipCentralizatorColoana::class, 'centralizator_id');
    }

    public function getColumnsTreeAttribute() {
        $columns = collect($this->columns)
            ->filter(function($column){
                return ! $column['group_id'];
            })
            ->map(function($item) {
                $item = collect($item)->only(['id', 'order_no', 'is_group', 'group_id', 'caption', 'type', 'width', 'props'])->toArray();
                return [
                    ...$item,
                    'children' => [],
                ];
            })
            ->sortBy('order_no')
            ->values()
            ->toArray();

        foreach($columns as $i => $column)
        {
            $columns[$i]['children'] = self::CreateColumnChildren($column, $this->columns);
        }
        return $columns;
    }
    
    public function getColumnsItemsAttribute() {
        $list = [];
        foreach($this->columns_tree as $i => $node)
        {
            if( count($node['children']) == 0)
            {
                $list[] = $node;
            }

            foreach($node['children'] as $j => $child)
            {
                $list[] = [
                    ...$child,
                    'children' => [],
                ];
            }
        }
        return $list;
    }

    public function getColumnsWithValuesAttribute() {   
        $result = collect($this->columns_items)->filter( function($item) {
            return count($item['children']) == 0;
        });
        return $result->toArray();
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



    private static function CreateColumnChildren($column, $current_columns) {

        $children = [];

        foreach($current_columns as $i => $item)
        {
            if(!! $item['group_id'] && ($item['group_id'] == $column['id']))
            {
                $children[] = $item;
            }
        }

        return collect($children)
            ->map(function($item) {
                $item = collect($item)->only(['id', 'order_no', 'is_group', 'group_id', 'caption', 'type', 'width', 'props'])->toArray();
                return $item;
            })
            ->sortBy('order_no')
            ->values()
            ->toArray();
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

    public static function GetDashboardItems($page, $customer_id) {
        $sql = "
            SELECT 
                `centralizatoare`.`id`,
                `centralizatoare`.`name`,
                `categories`.`name` AS category,
                COALESCE(is_associated, 0) AS is_associated,
                COALESCE(v_count_centralizatoare.count_items, 0) AS count_items
            FROM `centralizatoare`
            LEFT JOIN `customers-centralizatoare-asociere`
            ON (`centralizatoare`.id = `customers-centralizatoare-asociere`.centralizator_id) AND (" . $customer_id . " = `customers-centralizatoare-asociere`.customer_id)
            LEFT JOIN `categories`
            ON `categories`.id = `registers`.category_id
            LEFT JOIN 
                (
                    SELECT
                        centralizator_id,
                        COUNT(*) AS count_items
                    FROM `customers-centralizatoare`
                    WHERE customer_id = " . $customer_id . "
                    GROUP BY 1
                )
                v_count_centralizatoare
            ON `centralizatoare`.`id` = v_count_centralizatoare.centralizator_id
            WHERE `registers`." . ($page == 'centralizatoare' ? 'on_centralizatoare_page ' : 'on_gap_page ') . "> 0
        ";

        return \DB::select($sql);
    }

}