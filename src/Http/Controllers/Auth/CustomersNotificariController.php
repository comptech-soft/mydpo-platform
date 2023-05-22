<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerNotification;

class CustomersNotificariController extends Controller {
    
    public function index($customer_id, Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/customer-notificari/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

    public function getRecords(Request $r) {
        return CustomerNotification::getRecords($r->all());
    }

    public function getItems(Request $r) {
        return CustomerNotification::getItems($r->all());
    }

  
}