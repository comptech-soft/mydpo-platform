<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Role;

class RolesController extends Controller {
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/roles/index.js')
        );
    }

    public function getItems(Request $r) {
        return Role::getItems($r->all());
    }

    // public function doAction($action, Request $r) {
    //     return User::doAction($action, $r->all());
    // }

    public function saveRolePermissions(Request $r) {
        return Role::saveRolePermissions($r->all());
    }

    
}