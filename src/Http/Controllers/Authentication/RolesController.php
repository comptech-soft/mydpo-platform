<?php

namespace MyDpo\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Authentication\Role;

class RolesController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/authentication/roles/index.js'],
        );        
    }

    // public function index(Request $r) {
    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/roles/index.js')
    //     );
    // }

    public function getRecords(Request $r) {
        return Role::getItems($r->all());
    }

    // // public function doAction($action, Request $r) {
    // //     return User::doAction($action, $r->all());
    // // }

    // public function saveRolePermissions(Request $r) {
    //     return Role::saveRolePermissions($r->all());
    // }

    
}