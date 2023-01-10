<?php

namespace MyDpo\Performers\CustomerCursFile;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCursFile;

class AttachFiles extends Perform {

    public function Action() {

        foreach($this->input['files'] as $i => $file)
        {
            $this->procesFile($file);
        }

    }

    private function procesFile($file) {
        $ext = strtolower($file->extension());

        if(in_array($ext, ['xls', 'xlsx']))
        {
            $filename = \Str::slug(str_replace($file->extension(), '', $file->getClientOriginalName())) . '.' .  strtolower($file->extension());
            
            $result = $file->storeAs('fisiere-cursuri/' . $this->input['customer_curs_id'] . '/' . \Auth::user()->id, $filename, 's3');

            $inputdata = [
                'customer_curs_id' => $this->input['customer_curs_id'],
                'customer_id' => $this->input['customer_id'],
                'file_original_name' => $file->getClientOriginalName(),
                'file_original_extension' => $file->extension(),
                'file_full_name' => $filename,
                'file_mime_type' => $file->getMimeType(),
                'file_upload_ip' => request()->ip(),
                'file_size' => $file->getSize(),
                'url' => config('filesystems.disks.s3.url') . $result,
                'created_by' => \Auth::user()->id,
            ];

            // if(in_array($ext, ['jpg', 'jpeg', 'png']))
            // {
            //     $image = \Image::make($file);
            //     $inputdata = [
            //         ...$inputdata,
            //         'file_size' => $image->filesize(),
            //         'file_width' => $image->width(),
            //         'file_height' => $image->height(),
            //     ];
            // }

            $exist = CustomerCursFile::where('customer_curs_id', $this->input['customer_curs_id'])
                ->where('url', $inputdata['url'])
                ->first();

            if(! $exist )
            {
                return CustomerCursFile::create($inputdata);
            }

            $exist->deleted = 0;
            $exist->save();

            return $exist;
        }
        else
        {
            throw new \Exception('Fișier incorect.');
        }
    
    }
}