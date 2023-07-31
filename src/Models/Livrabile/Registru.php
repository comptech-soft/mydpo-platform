<?php

namespace MyDpo\Models\Livrabile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Columnable;
use MyDpo\Scopes\NotdeletedScope;
use MyDpo\Rules\Registru\UniqueName;

class Registru extends Model {

    use Itemable, Actionable, Columnable;

    protected $table = 'registers';

    protected $casts = [
        'props' => 'json',
        'body' => 'json',
        'order_no' => 'integer',
        'allow_upload_row_files' => 'integer',
        'has_departamente_column' => 'integer',
        'allow_versions' => 'integer',
        'has_status_column' => 'integer',
        'has_stare_column' => 'integer',
        'upload_folder_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
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
        'has_status_column',
        'has_stare_column',
        'description',
        'props',
        'body',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = [
        'human_type'
    ];

    protected $columnsDefinition = [
        'model' => \MyDpo\Models\Livrabile\RegistruColoana::class,
        'foreign_key' => 'register_id',
    ];

    protected static function booted() {
        static::addGlobalScope(new NotdeletedScope());
    }

    public function getHumanTypeAttribute() {
        if($this->type == 'registre')
        {
            return [
                'caption' => 'Registru',
                'color' => 'cyan',
            ];
        }

        return [
            'caption' => 'Audit',
            'color' => 'purple',
        ];
    }

    public function columns() {
        return $this->hasMany(RegistruColoana::class, 'register_id');
    }

    
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

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query()->with(['coloane']), __CLASS__))->Perform();
    // }



    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }

    // public static function doUpdate($input, $record) {
    //     $record->update($input);
        
    //     $columns = ['DEPARTAMENT', 'STATUS', 'STARE'];
    //     $fields = ['has_departamente_column', 'has_status_column', 'has_stare_column'];
    //     $captions = ['Departament', 'Vizibilitate', 'Status'];

    //     foreach($columns as $i => $column)
    //     {
    //         $data = [
    //             'register_id' => $record->id,
    //             'slug' => $column . $record->id . md5($i . time()),
    //             'caption' => $captions[$i],
    //             'is_group' => 0,
    //             'group_id' => NULL,
    //             'type' => $column,
    //             'order_no' => - $i - 1,
    //             'width' => 160,
    //         ];

    //         $exists = RegistruColoana::where('register_id', $record->id)->where('type', $column)->first(); 

    //         if($record->{$fields[$i]} == 1)
    //         {
    //             if($exists) 
    //             {
    //                 $exists->update($data);    
    //             }
    //             else
    //             {
    //                 RegistruColoana::create($data);
    //             }
    //         }
    //         else
    //         {
    //             if($exists) 
    //             {
    //                 $exists->delete();
    //             }
    //         }
    //     }



    //     return $record;
    // }

    public static function GetQuery() {
        return 
            self::query()
            ->select([
                'registers.id', 
                'registers.name', 
                'registers.type', 
                'registers.body', 
            ])
            ->withCount('columns');
    }
    
    public static function PrepareActionInput($action, $input) {

        if($action == 'insert')
        {
            $input['slug'] = \Str::slug($input['name']); 
            $input['description'] = '-'; 
            
        }

        return $input;
    }

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

            'type' => [
                'required',
                Rule::in(['registre', 'audit']),
            ],
           
        ];

        return $result;
    }

}