<?php

namespace MyDpo\Models\Livrabile;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Validation\Rule;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Centralizatorable;
use MyDpo\Scopes\NotdeletedScope;
use MyDpo\Rules\Registru\UniqueName;
// use MyDpo\Performers\Registru\SaveCustomerAsociere;

class TipRegistru extends Model {

    use Itemable, Actionable, Centralizatorable;

    protected $table = 'registers';

    protected $casts = [
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

    // protected $appends = [
    //     'human_type'
    // ];


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
        'model' => \MyDpo\Models\Livrabile\TipRegistruColoana::class,
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

    // public function getHumanTypeAttribute() {
    //     if($this->type == 'registre')
    //     {
    //         return [
    //             'caption' => 'Registru',
    //             'color' => 'cyan',
    //         ];
    //     }

    //     return [
    //         'caption' => 'Audit',
    //         'color' => 'purple',
    //     ];
    // }
   
    // public function getColumnsAttribute() {
    //     $t = $this->coloane->filter( function($item) {
    //         if($item->is_group == 1)
    //         {
    //             return TRUE;
    //         }

    //         if($item->is_group == 0 && $item->group_id == 0)
    //         {
    //             return TRUE;
    //         }
    //         return FALSE;
    //     })->map(function($item) {
    //         $item->column_type = $item->is_group == 1 ? 'group' : 'single';
    //         return $item;
    //     })->sortBy('order_no')->toArray();

    //     $r = [];
    //     foreach($t as $i => $item)
    //     {
    //         $r[]  = $item;
    //     }
       
    //     foreach($r as $i => $record)
    //     {
    //         $r[$i]['children'] = [];
    //         if($record['is_group'] == 1)
    //         {
    //             $children = $this->coloane->filter( function($item) use ($record) {
    //                 if($item->group_id == $record['id'])
    //                 {
    //                     return TRUE;
    //                 }
    //                 return FALSE;
    //             })->sortBy('order_no')->toArray();

    //             foreach($children as $j => $child)
    //             {
    //                 $r[$i]['children'][] = $child;
    //             }
    //         }
    //     }

    //     return $r;
    // }

    // function coloane() {
    //     return $this->hasMany(RegistruColoana::class, 'register_id');
    // }

    // public static function saveCustomerAsociere($input) {
    //     return (new SaveCustomerAsociere($input))->Perform();
    // }

    // public static function getCustomerAsociere($input) {

    //     $customer_id = $input['customer_id'];

    //     $q = self::query()->leftJoin(

    //         'customers-registers-asociate',
            
    //         function($j) use ($customer_id){
    //             $j
    //                 ->on('customers-registers-asociate.register_id', '=', 'registers.id')
    //                 ->where('customers-registers-asociate.customer_id', $customer_id);
    //         }

    //     )->select([
    //         'registers.id',
    //         'registers.name',
    //         'registers.type',
    //         'registers.body',
    //         'is_associated'
    //     ]);

    //     $types = ['registre', 'audit'];

    //     $records = $q->get()->filter(function($item) use ($input, $types){

    //         $visible = false;
    //         foreach( $types as $i => $type)
    //         {
    //             if( $input[$type] == 1)
    //             {
    //                 if($item->type == $type)
    //                 {
    //                     $visible = true;
    //                 }
    //             }
    //         }

    //         return $visible;

    //     });

    //     if(config('app.platform') == 'b2b')
    //     {
    //         $records = $records->filter( function($item) {
    //             return !! $item->is_associated;
    //         });
    //     }

    //     return $records->toArray();
    // }
    
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
    
    // public static function PrepareActionInput($action, $input) {
    //     if($action == 'insert')
    //     {
    //         $input['slug'] = \Str::slug($input['name']); 
    //         $input['description'] = '-'; 
            
    //     }
    //     return $input;
    // }

    public static function GetRules($action, $input) {
        if($action == 'delete')
        {
            return NULL;
        }

        $result = [
            'name' => [
                'required',
                new UniqueName($action, $input),
            ],

            // 'type' => [
            //     'required',
            //     Rule::in(['registre', 'audit']),
            // ],
           
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
            WHERE `registers`." . ($page == 'registre' ? 'on_registre_page ' : 'on_audit_page ') . "> 0
        ";

        return \DB::select($sql);
    }

}