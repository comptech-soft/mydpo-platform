<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerFolder;
use MyDpo\Models\Folder;

class CustomersFoldersController extends Controller
{
    
    public function getItems(Request $r) {
        return CustomerFolder::getItems($r->all());
    }

    public function getAllItems(Request $r) {
        return Folder::getItems($r->all());
    }

    public function getAncestors(Request $r) {
        return CustomerFolder::getAncestors($r->all());
    }

    public function getSummary(Request $r) {
        return CustomerFolder::getSummary($r->all());
    }

    public function saveOrderdFolders(Request $r) {
        return CustomerFolder::saveOrderdFolders($r->all());
    }
    
    public function doAction($action, Request $r) {
        return CustomerFolder::doAction($action, $r->all());
    }

}