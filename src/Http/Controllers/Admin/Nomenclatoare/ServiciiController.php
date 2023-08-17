<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Service;

class ServiciiController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/servicii/index.js']
        );        
    }

    // public function getItems(Request $r) {
    //     return Service::getItems($r->all());
    // }

    // public function doAction($action, Request $r) {
    //     return Service::doAction($action, $r->all());
    // }
}