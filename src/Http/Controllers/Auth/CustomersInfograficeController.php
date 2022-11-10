<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerFolder;

class CustomersInfograficeController extends Controller {
    
    public function index($customer_id) {

        $folder = new CustomerFolder([
            'customer_id' => $customer_id,
            'name' => 'Infografice',
            'platform' => 'admin',
            'type' => 'infografice',
            'parent_id' => NULL,
        ]);
        $folder->save();

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