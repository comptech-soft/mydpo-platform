<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\Documents\Folder;

class InfograficeController extends Controller {

    public function index($customer_id, Request $r) {

        Folder::CreateInfograficeFolder($customer_id);

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
    
}