<?php

namespace MyDpo\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;

class CustomerCentralizatoareController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/centralizatoare/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
                'gap' => 0,
                'centralizatoare' => 1,
            ],
        );        
    }

    





    // public function saveSettings(Request $r) {
    //     return CustomerCentralizator::saveSettings($r->all());
    // }

    // public function setAccess(Request $r) {
    //     return CustomerCentralizator::setAccess($r->all());
    // }

    // public function doImport(Request $r) {
    //     return CustomerCentralizator::doImport($r->all());
    // }


    
}