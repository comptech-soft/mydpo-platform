<?php

namespace MyDpo\Models\Livrabile\Registre;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Models\Livrabile\Categories\Category;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Admin\Livrabile\Tipuri\Centralizatorable;

use MyDpo\Scopes\NotdeletedScope;

use MyDpo\Rules\Registru\UniqueName;

class TipRegistru extends Model {

    use Itemable, Actionable, Centralizatorable;

    protected $table = 'registers';
    
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'props' => 'json',
        'body' => 'json',
        'order_no' => 'integer',
        'allow_upload_row_files' => 'integer',
        'has_departamente_column' => 'integer',
        'allow_versions' => 'integer',
        'has_stare_column' => 'integer',
        'upload_folder_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'on_registre_page' => 'integer',
        'on_audit_page' => 'integer',
        'has_nr_crt_column' => 'integer',
        'has_visibility_column' => 'integer',
        'has_status_column' => 'integer',
        'has_files_column' => 'integer',
        'has_department_column' => 'integer',
    ];
    
    protected $fillable = [
        'id',
        'name',
        'slug',
        'order_no',
        'type',
        'allow_upload_row_files',
        'allow_versions',
        'upload_folder_id',
        'has_departamente_column',
        'has_stare_column',
        'category_id',
        'description',
        'props',
        'body',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by',
        'on_registre_page',
        'on_audit_page',
        'has_nr_crt_column',
        'has_visibility_column',
        'has_status_column',
        'has_files_column',
        'has_department_column',
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
        'model' => \MyDpo\Models\Livrabile\Registre\TipRegistruColoana::class,
        'foreign_key' => 'register_id',
    ];

    protected static function booted() {
        static::addGlobalScope(new NotdeletedScope());
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id')->select(['id', 'name']);
    }

    public function columns() {
        return $this->hasMany(TipRegistruColoana::class, 'register_id');
    }
    
    public static function GetQuery() {
        return 
            self::query()
            ->leftJoin(
                'categories',
                function($j) {
                    $j->on('categories.id', '=', 'registers.category_id');
                }
            )
            ->select([
                'registers.id', 
                'registers.name', 
                'registers.category_id', 
                'registers.description', 
                'registers.on_registre_page', 
                'registers.on_audit_page',
                'registers.has_nr_crt_column',
                'registers.has_visibility_column',
                'registers.has_status_column',
                'registers.has_files_column',
                'registers.has_department_column',
                'registers.props'
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
                new UniqueName($action, $input),
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
                `registers`.`id`,
                `registers`.`name`,
                `categories`.`name` AS category,
                COALESCE(is_associated, 0) AS is_associated,
                COALESCE(v_count_registre.count_items, 0) AS count_items
            FROM `registers`
            LEFT JOIN `customers-registers-asociate`
            ON (`registers`.id = `customers-registers-asociate`.register_id) AND (" . $customer_id . " = `customers-registers-asociate`.customer_id)
            LEFT JOIN `categories`
            ON `categories`.id = `registers`.category_id
            LEFT JOIN 
                (
                    SELECT
                        register_id,
                        COUNT(*) AS count_items
                    FROM `customers-registers`
                    WHERE customer_id = " . $customer_id . "
                    GROUP BY 1
                )
                v_count_registre
            ON `registers`.`id` = v_count_registre.register_id
            WHERE ((`registers`.`deleted` = 0) OR (`registers`.`deleted` IS NULL)) AND (`registers`." . ($page == 'registre' ? 'on_registre_page ' : 'on_audit_page ') . "> 0)
        ";

        return \DB::select($sql);
    }

}