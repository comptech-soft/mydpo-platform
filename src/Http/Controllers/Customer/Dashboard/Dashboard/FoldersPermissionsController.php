<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\Documents\CustomerFolderPermission;

class FoldersPermissionsController extends Controller {
    
    public function getRecords(Request $r) {
        return CustomerFolderPermission::getRecords($r->all());
    }

}