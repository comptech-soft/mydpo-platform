<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\City;

class LocalitatiController extends Controller {
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/localitati/index.js')
        );
    }

    public function getItems(Request $r) {
        return City::getItems($r->all());
    }

    public function getRecords(Request $r) {
        return City::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return City::doAction($action, $r->all());
    }

}