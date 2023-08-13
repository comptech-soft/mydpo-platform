<?php

namespace MyDpo\Traits\Customer\Centralizatoare;

trait RowFileable {

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