<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;

class SysConfig extends Model {

    protected $table = 'sys-config';

    protected $casts = [
        'props' => 'json',
    ];

    protected $fillable = [
        'id',
        'type',
        'description',
        'code',
        'value',
        'width',
        'decimals',
        'suffix',
        'props',
        'file_original_name',
        'file_original_extension',
        'file_full_name',
        'file_mime_type',
        'file_upload_ip',
        'url',
        'value',
        'file_size',
        'file_width',
        'file_height',
        'created_by',
        'updated_by'
    ]; 

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

}