<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Permission;

class PermissionsController extends Controller {
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/permissions/index.js')
        );
    }

    public function getItems(Request $r) {
        return Permission::getItems($r->all());
    }
    
}