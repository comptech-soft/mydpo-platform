<?php

namespace MyDpo\Http\Controllers\Admin\Livrabile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\Registru;

class RegistreController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/registre/index.js']
        );        
    }

    public function getItems(Request $r) {
        return Registru::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return Registru::doAction($action, $r->all());
    }

}