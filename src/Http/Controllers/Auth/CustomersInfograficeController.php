<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class CustomersInfograficeController extends Controller {
    
    public function index($customer_id, Request $r) {

        dd('Bam bam....');

        return Response::View(
            '~templates.index', 
            asset('apps/customer-infografice/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

}