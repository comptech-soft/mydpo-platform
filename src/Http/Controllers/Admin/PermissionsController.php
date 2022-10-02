<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Permission;

class PermissionsController extends Controller {
    
    public function getItems(Request $r) {
        return Permission::getItems($r->all());
    }
    
}