<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRegistruRowFile extends Model {
   
    protected $table = 'customers-registers-rows-files';

    protected $casts = [
        'deleted' => 'integer',
        'row_id' => 'integer',
        'file' => 'json',
    ];

    protected $fillable = [
        'id',
        'row_id',
        'file',
        'created_by',
        'updated_by'
    ];

    public static function downloadFile($file_id) {

        $record = self::where('id', $file_id)->first();

        if(!! $record )
        {
            $path = $record->file->url;
            
            dd($path);
            $path = \Str::replace(config('filesystems.disks.s3.url'), '', $path);
            return \Storage::disk('s3')->download($path, $record->file_original_name);
        }

        return NULL;
    }

    
    
}