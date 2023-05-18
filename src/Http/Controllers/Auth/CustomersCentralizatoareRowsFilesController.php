<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerCentralizatorRowFile;

class CustomersCentralizatoareRowsFilesController extends Controller {
    
    public function getRecords(Request $r) {
        return CustomerCentralizatorRowFile::getRecords($r->all());
    }

    public function uploadFiles(Request $r) {
        return CustomerCentralizatorRowFile::uploadFiles($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerCentralizatorRowFile::doAction($action, $r->all());
    }
    
}