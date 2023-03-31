<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerRegistruRow;

class CustomersRegistreRowsController extends Controller {
    
    public function doAction($action, Request $r) {
        return CustomerRegistruRow::doAction($action, $r->all());
    }

    public function changeStatus(Request $r) {
        return CustomerRegistruRow::changeStatus($r->all());
    }
}