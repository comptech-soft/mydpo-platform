<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerDashboardItem;

class CustomersDashboardItemsController extends Controller
{
    
    public function getItems(Request $r) {
        return CustomerDashboardItem::getItems($r->all());
    }

}