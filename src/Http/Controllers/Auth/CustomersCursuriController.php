<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerCurs;

class CustomersCursuriController extends Controller {
    
    public function index($customer_id,Request $r) {

        dd(__METHOD__);

        return Response::View(
            '~templates.index', 
            asset('apps/customer-cursuri/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

    public function getItems(Request $r) {
        return CustomerCurs::getItems($r->all());
    }

    public function getSummary(Request $r) {
        return CustomerCurs::getSummary($r->all());
    }
    
}