<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\CustomerFolder;

class CustomerFile extends Model {

    
    protected $table = 'customers-files';

    protected $appends  = ['icon', 'is_image', 'is_office', 'just_name'];

    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'customer_id' => 'integer',
        'folder_id' => 'integer',
        'props' => 'json',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'folder_id',
        'file_original_name',
        'file_original_extension',
        'file_full_name',
        'file_mime_type',
        'file_upload_ip',
        'url',
        'file_size',
        'file_width',
        'file_height',
        'name',
        'deleted',
        'platform',
        'description',
        'status',
        'props',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getIconAttribute() {
        return config('app.url') . '/imgs/extensions/'. strtolower($this->file_original_extension) . '.png';
    }

    public function getIsImageAttribute() {
        $ext = strtolower($this->file_original_extension);
        return in_array($ext, ['jpg', 'jpeg', 'png']);
    }   

    public function getIsOfficeAttribute() {
        $ext = strtolower($this->file_original_extension);
        return in_array($ext, ['doc', 'docx', 'xls', 'xlsx']);
    }  

    public function getJustNameAttribute() {
        return \Str::replace('.' . $this->file_original_extension, '', $this->file_original_name);
    } 

    function folder() {
        return $this->belongsTo(CustomerFolder::class, 'folder_id');
    }

    public static function doInsert($input, $record) {

        dd($input);
        if( $input['file'] && is_array($input['file']) )
        {
            foreach($input['file'] as $file)
            {
                self::ProcessFile($file, $input);
            }
        }
        else
        {
            if($input['file'])
            {
                self::ProcessFile($input['file'], $input);
            }
        }
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

}