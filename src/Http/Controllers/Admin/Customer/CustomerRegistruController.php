<?php

namespace MyDpo\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\Livrabile\Registru;
// use MyDpo\Models\Customer\CustomerCentralizator;

class CustomerRegistruController extends Controller {
    
    public function index($customer_id, $register_id, Request $r) {

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/registru/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'centralizator_id' => $centralizator_id,
                'customer' => Customer::find($customer_id),
                'registru' => Registru::find($register_id),
            ],
        );        
    }

    // public function getRecords(Request $r) {
    //     return CustomerCentralizator::getRecords($r->all());
    // }

    // public function doAction($action, Request $r) {
    //     return CustomerCentralizator::doAction($action, $r->all());
    // }

    // public function getNextNumber(Request $r) {
    //     return CustomerCentralizator::getNextNumber($r->all());
    // }

}