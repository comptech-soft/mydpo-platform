<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\ELearning\CustomerCursFile;

class CursuriFilesController extends Controller {
    
    public function getRecords(Request $r) {
        return CustomerCursFile::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerCursFile::doAction($action, $r->all());
    }

    public function doDownload($id) {
        return CustomerCursFile::doDownload($id);
    }

}