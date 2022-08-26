<?php

namespace B2B\Classes\Comptech\Performers\Datatable;

use B2B\Classes\Comptech\Helpers\Perform;
use B2B\Classes\Comptech\Helpers\ExcelExport;
use B2B\Models\System\Export as ModelExport;

/**
 * inregistrarile exportate trebuie sa se gaseasca in input['items']
 */
class Export extends Perform {

    public $model = NULL;
    public $records = [];
    public $viewFile = NULL;        // blade-ul dupa care se face exportul
    public $columns = [];           // coloanele ce se exporta

    public function __construct($input, $model, $viewFile, $columns) {
        parent::__construct($input, NULL, NULL);
        $this->model = $model;
        $this->records = $this->input['items'];
        $this->viewFile = $viewFile;
        $this->columns = $columns;  
    }

    public function createUserFolder($userId) {
        if(! \Storage::exists('public/exports/' . $userId) )
        {
            \Storage::disk('public')->makeDirectory('exports/' . $userId, 0777);
        }
    }

    public function doExportExcel() {
        
        $export = new ExcelExport($this->viewFile, $this->records, $this->columns);

        $this->createUserFolder($userId = \Str::padLeft(\Sentinel::check()->id, 10, '0'));
              
        $filename = 'public/exports/' . $userId . '/' . $this->input['fileName'] . '.xlsx';
                
        \Excel::store($export, $filename, NULL, NULL, ['visibility' => 'public']);

        ModelExport::create([
            'card_id' => NULL,
            'user_id' => \Sentinel::check()->id,
            'customer_id' => NULL,
            'exported_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'model' => $this->model,
            'items_data' => NULL,
            'page' => NULL,
            'type' => $this->input['type'],
            'columns' =>NULL,
            'filename' => $filename,
            'size' => \File::size($file = \Str::replace('public', 'storage', $filename)),
            'url' => $url = asset($file),
            'created_by' => \Sentinel::check()->id,
            'updated_by' => \Sentinel::check()->id,
        ]);

        return $url;
    }

    public function doExportPdf() {

        $this->createUserFolder($userId = \Str::padLeft(\Sentinel::check()->id, 10, '0'));

        $filename = 'public/exports/' . $userId . '/' . $this->input['filename'] . '.pdf';
        $view = call_user_func([$this->model, 'getPdfView']);

        $pdf = \PDF::loadView($view, [
            'records' => $this->records,
        ]);

        $content = $pdf->download()->getOriginalContent();
        \Storage::put($filename, $content);

        return asset(\Str::replace('public', 'storage', $filename));
    }

    public function DispatchExport() {
        $method = 'doExport' . ucfirst($this->input['type']);
        if(method_exists($this->model, $method) )
        {
            return call_user_func([$this->model, $method], $this->input);
        }
        return $this->{$method}();
    }

    public function Action() {
        $this->payload = [
            'url' => $this->DispatchExport(),
            'records' => $this->records,
        ];
    }

}