<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Performers\CustomerCentralizatorRowFile\UploadFiles;

class CustomerCentralizatorRowFile extends Model {

    use Itemable, Actionable;

    protected $table = 'customers-centralizatoare-rows-files';

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
        'is_office',
        'is_pdf',
        'icon'
    ];

    public function getIsImageAttribute() {
        $ext = strtolower($this->file['file_original_extension']);
        return in_array($ext, ['jpg', 'jpeg', 'png']);
    }   

    public function getIsOfficeAttribute() {
        $ext = strtolower($this->file['file_original_extension']);
        return in_array($ext, ['doc', 'docx', 'xls', 'xlsx']);
    }  

    public function getIsPdfAttribute() {
        $ext = strtolower($this->file['file_original_extension']);
        return in_array($ext, ['pdf']);
    } 

    public function getIconAttribute() {
        return config('app.url') . '/imgs/extensions/'. strtolower($this->file['file_original_extension']) . '.png';
    }

    public static function uploadFiles($input) {
        return (new UploadFiles($input))->Perform();
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