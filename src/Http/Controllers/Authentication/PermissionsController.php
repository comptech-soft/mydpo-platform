<?php

namespace MyDpo\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Permission;

class PermissionsController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/authentication/permissions/index.js'],
        );        
    }


    // public function getItems(Request $r) {
    //     return Permission::getItems($r->all());
    // }

    // public function doAction($action, Request $r) {
    //     return Permission::doAction($action, $r->all());
    // }

    // public function reorder(Request $r) {
    //     return Permission::reorder($r->all());
    // }
    
}