<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerStatus;

class CustomersStatusesController extends Controller
{
    
    public function getItems(Request $r) {
        return CustomerStatus::getItems($r->all());
    }

}