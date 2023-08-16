<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\System\Country;

class CountriesController extends Controller {
    
    public function getItems(Request $r) {
        return Country::getItems($r->all());
    }

    public function getRecords(Request $r) {
        return Country::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Country::doAction($action, $r->all());
    }

}