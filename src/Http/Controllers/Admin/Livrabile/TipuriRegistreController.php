<?php

namespace MyDpo\Http\Controllers\Admin\Livrabile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Livrabile\Registru;

class TipuriRegistreController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/tipuri-registre/index.js']
        );        
    }

    // public function getRecords(Request $r) {
    //     return Registru::getRecords($r->all());
    // }

    // public function doAction($action, Request $r) {
    //     return Registru::doAction($action, $r->all());
    // }

    // public function getCustomerAsociere(Request $r) {
    //     return Registru::getCustomerAsociere($r->all());
    // }

    // public function saveCustomerAsociere(Request $r) {
    //     return Registru::saveCustomerAsociere($r->all());
    // }
    

}