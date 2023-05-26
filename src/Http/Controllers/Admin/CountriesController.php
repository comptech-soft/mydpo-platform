<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Country;

class CountriesController extends Controller {
    
    public function getItems(Request $r) {
        return Country::getItems($r->all());
    }

    public function getRecords(Request $r) {
        return Country::getRecords($r->all());
    }

}