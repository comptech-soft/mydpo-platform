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

    protected $appends = [
        'is_image', 
        'is_office'
    ];

    public function getIsImageAttribute() {
        $ext = strtolower($this->file['file_original_extension']);
        return in_array($ext, ['jpg', 'jpeg', 'png']);
    }   

    public function getIsOfficeAttribute() {
        $ext = strtolower($this->file['file_original_extension']);
        return in_array($ext, ['doc', 'docx', 'xls', 'xlsx']);
    }  

    public static function downloadFile($id) {

        $record = self::where('id', $id)->first();

        if(!! $record )
        {
            $path = $record->file['url'];
            
            $path = \Str::replace(config('filesystems.disks.s3.url'), '', $path);

            return \Storage::disk('s3')->download($path, $record->file['file_original_name']);
        }

        return NULL;
    }

}