<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\Documents\CustomerFile;

class FilesController extends Controller {

    public function getRecords(Request $r) {
        return CustomerFile::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerFile::doAction($action, $r->all());
    }

    // public function changeFilesStatus(Request $r) {
    //     return CustomerFile::changeFilesStatus($r->all());
    // }

    // public function moveFiles(Request $r) {
    //     return CustomerFile::moveFiles($r->all());
    // }
    
    // public function deleteFiles(Request $r) {
    //     return CustomerFile::deleteFiles($r->all());
    // }
    

}