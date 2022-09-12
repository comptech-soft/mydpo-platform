<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerChestionar;

class CustomersChestionareController extends Controller
{
    
    public function getItems(Request $r) {
        return CustomerChestionar::getItems($r->all());
    }

    public function getSummary(Request $r) {
        return CustomerChestionar::getSummary($r->all());
    }
    
}