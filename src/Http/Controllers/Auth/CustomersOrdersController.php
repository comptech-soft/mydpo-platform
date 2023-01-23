<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerOrder;

class CustomersOrdersController extends Controller {
    
    public function index($customer_id, Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/customer-comenzi/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

    public function getItems(Request $r) {
        return CustomerOrder::getItems($r->all());
    }
    
    public function doAction($action, Request $r) {
        return CustomerOrder::doAction($action, $r->all());
    }

}