<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerFolder;

class CustomersFoldersController extends Controller
{
    
    public function getItems(Request $r) {
        return CustomerFolder::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerFile::doAction($action, $r->all());
    }

}