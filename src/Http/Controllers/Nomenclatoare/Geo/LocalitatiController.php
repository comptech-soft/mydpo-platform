<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Geo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\System\City;

class LocalitatiController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/localitati/index.js']
        );        
    }

    public function getRecords(Request $r) {
        return City::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return City::doAction($action, $r->all());
    }

}