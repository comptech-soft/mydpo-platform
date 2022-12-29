<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerCurs;

class CustomersCursuriAccesCursController extends Controller {
    
    public function index($customer_id, $customer_curs_id, Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/customer-cursuri-acces-curs/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
                'customer_curs_id' => $customer_curs_id,
            ]
        );
    }

    
    
}