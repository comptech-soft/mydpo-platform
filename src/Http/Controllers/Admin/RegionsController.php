<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Region;

class RegionsController extends Controller {
    
    public function getItems(Request $r) {
        return Region::getItems($r->all());
    }

    public function getRecords(Request $r) {
        return Country::getRecords($r->all());
    }

}