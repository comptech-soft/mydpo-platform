<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\Dashboard\Item;
use MyDpo\Models\Customer\Dashboard\Entity;
use MyDpo\Models\Authentication\UserSetting;

class DashboardController extends Controller {
    
    /**
     * Dashboard-ul unui client
     * Se stie id-ul clientului
     * Se afiseaza linkuri catre livrabile si catre entitati
     */
    public function index($customer_id, Request $r) {

        UserSetting::saveActiveCustomer([
            'user_id' => \Auth::user()->id,
            'platform' => config('app.platform'),
            'customer_id' => $customer_id
        ]);

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/dashboard/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
                'dashboard_items' => Item::getByColumns(),
                'entities_items' => Entity::GetByPlatform(),
                'customer_user' => \Auth::user(),
            ],
        );        
    }

    public function doAction($action, Request $r) {
        return Item::doAction($action, $r->all());
    }

}