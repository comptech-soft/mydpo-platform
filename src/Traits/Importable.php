<?php

namespace MyDpo\Traits;

trait Importable { 

    public static function doImport($input, $record) {

        dd($input['file']);

        \Excel::import(self::GetExporter($input), $this->file);

        $this->payload = [
            'record' => NULL,
        ];

       
        dd($input, $record);
        // if(! \Storage::exists('public/exports/' . \Auth::user()->id) )
        // {
        //     \Storage::disk('public')->makeDirectory('exports/' . \Auth::user()->id, 0777);
        // }

        // $file_name = 'public/exports/' . \Auth::user()->id. '/' . $input['file_name'];

        // \Excel::store(
        //     , 
        //     $file_name, 
        //     NULL, 
        //     NULL, 
        //     ['visibility' => 'public']
        // );

        // $file = \Str::replace('public', 'storage', $file_name);

        // return [
        //     'url' => asset($file),
        // ];

    }
    
}