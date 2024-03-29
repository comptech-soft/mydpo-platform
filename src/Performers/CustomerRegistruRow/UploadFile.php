<?php

namespace MyDpo\Performers\CustomerRegistruRow;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerRegistruRowFile;

class UploadFile extends Perform {

    public function Action() {
        
        $file = $this->input['file'];

        $ext = strtolower($file->extension());

        $filename = md5(time()) . '-' . \Str::slug(str_replace($file->extension(), '', $file->getClientOriginalName())) . '.' .  strtolower($file->extension());

        $result = $file->storeAs('registre-rows/' .  \Auth::user()->id, $filename, 's3');

        $inputdata = [
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

        CustomerRegistruRowFile::create([
            'row_id' => $this->input['row_id'],
            'file' => $inputdata,
            'created_by' => \Auth::user()->id,
        ]);
        
    }
}