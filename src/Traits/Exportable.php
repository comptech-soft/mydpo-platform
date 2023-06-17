<?php

namespace MyDpo\Traits;

trait Exportable { 

    public static function doExport($input, $record) {
       
        if(! \Storage::exists('public/exports/' . \Auth::user()->id) )
        {
            \Storage::disk('public')->makeDirectory('exports/' . \Auth::user()->id, 0777);
        }

        $file_name = 'public/exports/' . \Auth::user()->id. '/' . $input['file_name'];

        dd(self::GetExporter($input));

    }
    
}