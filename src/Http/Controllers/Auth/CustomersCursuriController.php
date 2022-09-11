<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerCurs;

class CustomersCursuriController extends Controller
{
    
    public function getItems(Request $r) {
        return CustomerCurs::getItems($r->all());
    }

    public function getSummary(Request $r) {
        return CustomerCurs::getSummary($r->all());
    }
    
}