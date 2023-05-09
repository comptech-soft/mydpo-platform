<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
// use MyDpo\Models\CustomerCentralizator;

class CustomerCentralizatorController extends Controller {
    
    public function index($customer_id, $centralizator_id, Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/customer-centralizator/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
                'centralizator_id' => $centralizator_id,
            ]
        );
    }

    // public function getItems(Request $r) {
    //     return CustomerCentralizator::getItems($r->all());
    // }

    // public function getSummary(Request $r) {
    //     return CustomerCentralizator::getSummary($r->all());
    // }
    
}