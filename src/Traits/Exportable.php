<?php

namespace MyDpo\Traits;

trait Exportable { 

    public static function doExport($input, $record) {
       
        if(! \Storage::exists('public/exports/' . \Auth::user()->id) )
        {
            \Storage::disk('public')->makeDirectory('exports/' . \Auth::user()->id, 0777);
        }

        $file_name = 'public/exports/' . \Auth::user()->id. '/' . $input['file_name'];

        if( array_key_exists('html', $input) && ($input['html'] == 1))
        {
            return self::GetExporter($input)->view()->render();
        }

        \Excel::store(
            self::GetExporter($input), 
            $file_name, 
            NULL, 
            NULL, 
            ['visibility' => 'public']
        );

        $file = \Str::replace('public', 'storage', $file_name);

        return [
            'url' => asset($file),
        ];

    }
    
}