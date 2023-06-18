<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Exportable;
use MyDpo\Traits\Importable;
use MyDpo\Traits\Truncable;
use MyDpo\Exports\Translation\Exporter;
use MyDpo\Imports\Admin\Translation\Importer;
use MyDpo\Performers\Translation\CreateKeys;
use MyDpo\Performers\Translation\Activate;

class Translation extends Model 
{

    use Itemable, Actionable, Exportable, Importable, Truncable;

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

    protected static function GetImporter($input) {
        return new Importer($input); 
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
    

    public static function createKeys($input) {
        return (new CreateKeys($input))->Perform();
    }

    public static function GetRules($action, $input) {
       
        if( ! in_array($action, ['insert', 'update']) )
        {
            return NULL;
        }

        $result = [
            'ro' => 'required|unique:translations,ro',
        ];

        return $result;
    }

}