<?php

namespace MyDpo\Models\Customer\ELearning;

use Illuminate\Database\Eloquent\Model;

// use MyDpo\Performers\CustomerCursFile\AttachFiles;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class CustomerCursFile extends Model {

    use Itemable, Actionable;
    
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

    protected $appends = [
        'is_image', 
        'is_office',
        'is_pdf',
        'icon'
    ];

    public function getIsImageAttribute() {
        return in_array(strtolower($this->file_original_extension), ['jpg', 'jpeg', 'png']);
    }   

    public function getIsOfficeAttribute() {
        return in_array(strtolower($this->file_original_extension), ['doc', 'docx', 'xls', 'xlsx']);
    }  

    public function getIsPdfAttribute() {
        return in_array(strtolower($this->file_original_extension), ['pdf']);
    } 

    public function getIconAttribute() {
        return config('app.url') . '/imgs/extensions/'. strtolower($this->file_original_extension) . '.png';
    }

    public static function doInsert($input, $record) {
        foreach($input['files'] as $i => $file)
        {
            self::attachFile($file, $input['customer_curs_id'], $input['customer_id']);
        }
        CustomerCurs::Sync($input['customer_id']);
    }

    public static function attachFile($file, $customer_curs_id, $customer_id) {

        if(in_array($ext = strtolower($file->extension()), ['xls', 'xlsx', 'pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png']))
        {
            $filename = \Str::slug(str_replace($file->extension(), '', $file->getClientOriginalName())) . '.' .  strtolower($file->extension());

            $result = $file->storeAs('fisiere-cursuri/' . $customer_curs_id . '/' . \Auth::user()->id, $filename, 's3');

            $input = [
                'customer_curs_id' => $customer_curs_id,
                'customer_id' => $customer_id,
                'file_original_name' => $file->getClientOriginalName(),
                'file_original_extension' => $file->extension(),
                'file_full_name' => $filename,
                'file_mime_type' => $file->getMimeType(),
                'file_upload_ip' => request()->ip(),
                'file_size' => $file->getSize(),
                'url' => config('filesystems.disks.s3.url') . $result,
                'platform' => config('app.platform'),
                'created_by' => \Auth::user()->id,
                'deleted' => 0,
            ];

            if(in_array($ext, ['jpg', 'jpeg', 'png']))
            {
                $image = \Image::make($file);
                $input = [
                    ...$input,
                    'file_size' => $image->filesize(),
                    'file_width' => $image->width(),
                    'file_height' => $image->height(),
                ];
            }
            
            $record = CustomerCursFile::where('customer_curs_id', $customer_curs_id)->where('url', $input['url'])->first();

            if(! $record )
            {
                $record = self::create($input);
            }
            else
            {
                $record->update($input);
            }
        }
    }

    public static function doDownload($id) {

        $record = self::where('id', $id)->first();

        if(!! $record )
        {
            $path = $record->url;
            
            $path = \Str::replace(config('filesystems.disks.s3.url'), '', $path);

            return \Storage::disk('s3')->download($path, $record->file_original_name);
        }

        return NULL;
    }

    // public static function downloadFile($customer_id, $file_id) {

    //     $record = self::where('customer_id', $customer_id)->where('id', $file_id)->first();

    //     if(!! $record )
    //     {
    //         $path = $record->url;          
    //         $path = \Str::replace(config('filesystems.disks.s3.url'), '', $path);
    //         return \Storage::disk('s3')->download($path, $record->file_original_name);
    //     }

    //     return NULL;
    // }

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query(), __CLASS__))->Perform();
    // }

    // public static function attachFiles($input) {
    //     return (new AttachFiles($input))->Perform();
    // }

}