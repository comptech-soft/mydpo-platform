<?php

namespace MyDpo\Performers\CustomerCentralizatorRowFile;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\CustomerCentralizatorRowFile;

class UploadFiles extends Perform {

    public function Action() {

        if($this->files)
        {
            foreach($this->files as $i => $file)
            {
                $this->attachFile($file);
            }
        }

        $this->payload = [
            'record' => NULL,
        ];
    }

    protected function attachFile($file) {

        $ext = strtolower($file->extension());

        $ext = strtolower($file->extension());

        if(! in_array( strtolower($ext), ['pdf', 'jpeg', 'jpg', 'png', 'xls', 'xlsx', 'doc', 'docx']))
        {
            return NULL;
        }
        
        $filename = md5(time()) . '-' . \Str::slug(str_replace($file->extension(), '', $file->getClientOriginalName())) . '.' .  strtolower($file->extension());

        $result = $file->storeAs('centralizatoare-rows/' .  \Auth::user()->id, $filename, 's3');

        $input = [
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
            $input = [
                ...$input,
                'file_size' => $image->filesize(),
                'file_width' => $image->width(),
                'file_height' => $image->height(),
            ];
        }

        CustomerCentralizatorRowFile::create([
            'row_id' => $this->row_id,
            'file' => $input,
            'created_by' => \Auth::user()->id,
        ]);
    }
}