<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerFile;

class CustomersFilesController extends Controller
{
    public function getItems(Request $r) {
        return CustomerFile::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerFile::doAction($action, $r->all());
    }

    public function changeFilesStatus(Request $r) {
        return CustomerFile::changeFilesStatus($r->all());
    }

    public function moveFiles(Request $r) {
        return CustomerFile::moveFiles($r->all());
    }
    
    public function deleteFiles(Request $r) {
        return CustomerFile::deleteFiles($r->all());
    }
    

}