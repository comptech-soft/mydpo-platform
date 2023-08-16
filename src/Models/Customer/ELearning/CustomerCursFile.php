<?php

namespace MyDpo\Models\Customer\ELearning;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Performers\CustomerCursFile\AttachFiles;

class CustomerCursFile extends Model {

    protected $table = 'customers-cursuri-files';

    protected $casts = [
        'props' => 'json',
        'customer_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'customer_curs_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_curs_id',
        'customer_id',
        'platform',
        'name',
        'description',
        'file_original_name',
        'file_original_extension',
        'file_full_name',
        'file_mime_type',
        'file_upload_ip',
        'url',
        'file_size',
        'file_width',
        'file_height',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function downloadFile($customer_id, $file_id) {

        $record = self::where('customer_id', $customer_id)->where('id', $file_id)->first();

        if(!! $record )
        {
            $path = $record->url;          
            $path = \Str::replace(config('filesystems.disks.s3.url'), '', $path);
            return \Storage::disk('s3')->download($path, $record->file_original_name);
        }

        return NULL;
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function attachFiles($input) {
        return (new AttachFiles($input))->Perform();
    }

}