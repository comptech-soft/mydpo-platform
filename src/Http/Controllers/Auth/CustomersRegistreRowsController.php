<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerRegistruRow;
use MyDpo\Models\CustomerRegistruRowFile;

class CustomersRegistreRowsController extends Controller {
    
    public function doAction($action, Request $r) {
        return CustomerRegistruRow::doAction($action, $r->all());
    }

    public function changeStatus(Request $r) {
        return CustomerRegistruRow::changeStatus($r->all());
    }

    public function changeStare(Request $r) {
        return CustomerRegistruRow::changeStare($r->all());
    }

    public function deleteRows(Request $r) {
        return CustomerRegistruRow::deleteRows($r->all());
    }

    public function uploadFile(Request $r) {
        return CustomerRegistruRow::uploadFile($r->all());
    }

    public function loadFiles(Request $r) {
        return CustomerRegistruRow::loadFiles($r->all());
    }

    public function deleteFile(Request $r) {
        return CustomerRegistruRow::deleteFile($r->all());
    }

    public function downloadFile($id, Request $r) {
        return CustomerRegistruRowFile::downloadFile($id);
    }
}