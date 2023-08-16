<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\Customer\Dashboard\Item;
use MyDpo\Models\Customer\Dashboard\Entity;

class DashboardController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/dashboard/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
                'dashboard_items' => Item::getByColumns(),
                'entities_items' => Entity::getByPlatform(),
            ],
        );        
    }

    public function doAction($action, Request $r) {
        return CustomerDashboardItem::doAction($action, $r->all());
    }

}