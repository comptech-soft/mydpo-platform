<?php

namespace MyDpo\Http\Controllers\Customer\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\Customer\Notifications\Notification;

class NotificariController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/notificari/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }

    public function getRecords(Request $r) {
        return Notification::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Notification::doAction($action, $r->all());
    }
      
}