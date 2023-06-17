<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Exportable;
use MyDpo\Exports\Translation\Exporter;

// use MyDpo\Performers\Translation\CreateKey;
// use MyDpo\Performers\Translation\CreateFile;
use MyDpo\Performers\Translation\Activate;
// use MyDpo\Performers\Translation\Export;
// use MyDpo\Helpers\Performers\Datatable\GetItems;   
// use MyDpo\Helpers\Performers\Datatable\DoAction; 

class Translation extends Model 
{

    use Itemable, Actionable, Exportable;

    protected $table = 'translations';

    protected $fillable = [
        'id',
        'ro',
        'en',      
        'created_by',
        'updated_by',
    ];

    protected static function GetExporter($input) {
        return new Exporter($input); 
    }


    // public static function createFile($input) {
    //     return (new CreateFile($input))->Perform();
    // }

    public static function activate($input) {
        return (new Activate($input))->Perform();
    }

    // public static function doExport($input, $record) {
    //     // return (new Export($input))->Perform();
    // }
    

    // public static function createKey($input) {
    //     return (new CreateKey($input))->Perform();
    // }

    // public static function ToJavascriptVars($locale) {

    //     $records = self::whereNotNull($locale)->get();

    //     $r = [];
    //     foreach($records as $i => $record)
    //     {
    //         $r[$record->ro] = $record->en;
    //     }

    //     return $r;
    // }

}