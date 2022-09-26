<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerFolderPermission;

class CustomersFoldersPermssionsController extends Controller {
    
    public function getItems(Request $r) {
        return CustomerFolderPermission::getItems($r->all());
    }

}