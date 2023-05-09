<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerCentralizator;

class CustomersCentralizatoareController extends Controller {
    
    public function index($customer_id, Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/customer-centralizatoare/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

    public function getRecords(Request $r) {
        return CustomerCentralizator::getRecords($r->all());
    }

    public function getSummary(Request $r) {
        return CustomerCentralizator::getSummary($r->all());
    }
    
}