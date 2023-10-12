<?php

namespace MyDpo\Models\Livrabile\Centralizatoare;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Models\Livrabile\Categories\Category;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Admin\Livrabile\Tipuri\Centralizatorable;

use MyDpo\Scopes\NotdeletedScope;

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

        'columns_count' => 'integer',

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
        'model' => \MyDpo\Models\Livrabile\Centralizatoare\TipCentralizatorColoana::class,
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
            ON `categories`.id = `centralizatoare`.category_id
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
            WHERE `centralizatoare`." . ($page == 'centralizatoare' ? 'on_centralizatoare_page ' : 'on_gap_page ') . "> 0
        ";

        return \DB::select($sql);
    }

}