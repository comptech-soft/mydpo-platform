<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;

class InfograficeController extends Controller {

    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/documents/index.js'],
            payload: [
                'type' => 'infografice',
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }
    
    // public function index($customer_id) {

    //     $folder = CustomerFolder::where('customer_id', $customer_id)
    //         ->where('name', 'Infografice')
    //         ->where('platform', 'admin')
    //         ->where('type', 'infografice')
    //         ->whereNull('parent_id')
    //         ->where('deleted', 0)
    //         ->first();

    //     if(! $folder)
    //     {
    //         $folder = new CustomerFolder([
    //             'customer_id' => $customer_id,
    //             'name' => 'Infografice',
    //             'platform' => 'admin',
    //             'type' => 'infografice',
    //             'parent_id' => NULL,
    //             'deleted' => 0,
    //         ]);
    //         $folder->save();
    //     }

    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/customer-infografice/index.js'),
    //         [], 
    //         [
    //             'customer_id' => $customer_id,
    //         ]
    //     );
    // }

}