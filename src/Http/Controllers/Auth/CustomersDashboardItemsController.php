<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerStatus;

class CustomersDashboardItemsController extends Controller
{
    
    public function getItems(Request $r) {
        return CustomerStatus::getItems($r->all());
    }

}