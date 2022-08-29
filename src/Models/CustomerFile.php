<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\CustomerFolder;
use MyDpo\Performers\CustomerFile\ChangeFilesStatus;

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

    public static function changeFilesStatus($input) {
        return (new ChangeFilesStatus($input))
            ->SetSuccessMessage('Schimbare status cu success!')
            ->Perform();
    } 

    public static function GetRules($action, $input) {
        if($action == 'delete')
        {
            return NULL;
        }
        $result = [
            'customer_id' => 'required|exists:customers,id',
            'file_original_extension' => 'required',
            'platform' => 'in:admin,b2b',
            'folder_id' => 'required|exists:customers_folders,id',
            'url' => 'required',
        ];
        return $result;
    }

    public static function doAction($action, $input) {

        $nput['file_original_name'] = $input['name'];
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function doInsert($input, $record) {
        if( ! array_key_exists('files', $input) )
        {
            throw new \Exception('Nu am fișiere.');
        }

        if( ! is_array($input['files']) )
        {
            $input['files'] = [$input['files']];
        }

        foreach($input['files'] as $file)
        {
            $record = self::ProcessFile($file, $input);
        }

        return $record;
    }

    public static function ProcessFile($file, $input) {
        
        $ext = strtolower($file->extension());

        if(in_array($ext, ['jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx', 'pdf', 'txt', 'rar', 'zip']))
        {
            $filename = md5(time()) . '-' . \Str::slug(str_replace($file->extension(), '', $file->getClientOriginalName())) . '.' .  strtolower($file->extension());
            
            $result = $file->storeAs('customers-documents/' . $input['customer_id'] . '/' . \Auth::user()->id, $filename, 's3');

            $inputdata = [
                ...$input,
                'file_original_name' => $file->getClientOriginalName(),
                'file_original_extension' => $file->extension(),
                'file_full_name' => $filename,
                'file_mime_type' => $file->getMimeType(),
                'file_upload_ip' => request()->ip(),
                'file_size' => $file->getSize(),
                'url' => config('filesystems.disks.s3.url') . $result,
                'created_by' => \Auth::user()->id,
            ];

            if(in_array($ext, ['jpg', 'jpeg', 'png']))
            {
                $image = \Image::make($file);
                $inputdata = [
                    ...$inputdata,
                    'file_size' => $image->filesize(),
                    'file_width' => $image->width(),
                    'file_height' => $image->height(),
                ];
            }

            return self::create($inputdata);
        }
        else
        {
            throw new \Exception('Fișier incorect.');
        }
    }

}