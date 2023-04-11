<?php

namespace MyDpo\Performers\CustomerRegistruRow;

use MyDpo\Helpers\Perform;
// use MyDpo\Models\CustomerRegistruRow;

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

        dd($inputdata);
        
    }
}