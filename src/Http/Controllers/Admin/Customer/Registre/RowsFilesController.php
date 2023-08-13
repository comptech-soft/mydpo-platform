<?php

namespace MyDpo\Http\Controllers\Admin\Customer\Registre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\Registre\RowFile;

class RowsFilesController extends Controller {
    
    public function getRecords(Request $r) {
        return RowFile::getRecords($r->all());
    }

    public function uploadFiles(Request $r) {
        return RowFile::uploadFiles($r->all());
    }

    public function downloadFile($id) {
        return RowFile::downloadFile($id);
    }

    public function doAction($action, Request $r) {
        return RowFile::doAction($action, $r->all());
    }
    
}