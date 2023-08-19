<?php

namespace MyDpo\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Authentication\Role;

class AllUsersController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/authentication/all-users/index.js'],
        );        
    }

    // public function getRecords(Request $r) {
    //     return Role::getRecords($r->all());
    // }

    // public function doAction($action, Request $r) {
    //     return Role::doAction($action, $r->all());
    // }

    // // public function saveRolePermissions(Request $r) {
    // //     return Role::saveRolePermissions($r->all());
    // // }

    
}