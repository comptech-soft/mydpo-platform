<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Authentication\Role;

class RolesController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/auth/roles/index.js'],
        );        
    }

    public function getRecords(Request $r) {
        return Role::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Role::doAction($action, $r->all());
    }

    // public function saveRolePermissions(Request $r) {
    //     return Role::saveRolePermissions($r->all());
    // }

    
}