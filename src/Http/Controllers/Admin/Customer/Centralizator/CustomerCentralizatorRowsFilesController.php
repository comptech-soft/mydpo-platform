<?php

namespace MyDpo\Http\Controllers\Admin\Customer\Centralizator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\CustomerCentralizatorRowFile;

class CustomerCentralizatorRowsFilesController extends Controller {
    
    public function getRecords(Request $r) {
        return CustomerCentralizatorRowFile::getRecords($r->all());
    }

    public function uploadFiles(Request $r) {
        return CustomerCentralizatorRowFile::uploadFiles($r->all());
    }

    public function downloadFile($id) {
        return CustomerCentralizatorRowFile::downloadFile($id);
    }

    public function doAction($action, Request $r) {
        return CustomerCentralizatorRowFile::doAction($action, $r->all());
    }
    
}