<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Performers\CustomerCentralizatorRowFile\UploadFiles;

class CustomerCentralizatorRowFile extends Model {

    use Itemable;

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

    public function getIconAttribute() {
        return config('app.url') . '/imgs/extensions/'. strtolower($this->file_original_extension) . '.png';
    }

    public static function uploadFiles($input) {
        return (new UploadFiles($input))->Perform();
    }

}