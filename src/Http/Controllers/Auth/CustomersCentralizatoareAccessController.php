<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerCentralizatorAccess;

class CustomersCentralizatoareAccessController extends Controller {
    
    public function getRecords(Request $r) {
        return CustomerCentralizatorAccess::getRecords($r->all());
    }

}