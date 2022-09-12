<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerCentralizator;

class CustomersCentralizatoareController extends Controller
{
    
    public function getItems(Request $r) {
        return CustomerCentralizator::getItems($r->all());
    }

    public function getSummary(Request $r) {
        return CustomerCentralizator::getSummary($r->all());
    }
    
}