<?php

namespace MyDpo\Http\Controllers\Admin\Customer\Registre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Customer;
// use MyDpo\Models\Livrabile\Registru;
// use MyDpo\Models\Customer\CustomerRegister;

class CustomerTipRegistruController extends Controller {
    
    public function index($customer_id, $register_id, Request $r) {

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/registru/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'register_id' => $register_id,
                'customer' => Customer::find($customer_id),
                'registru' => Registru::find($register_id),
            ],
        );        
    }

    // public function getRecords(Request $r) {
    //     return CustomerRegister::getRecords($r->all());
    // }

    // public function doAction($action, Request $r) {
    //     return CustomerRegister::doAction($action, $r->all());
    // }

    // public function getNextNumber(Request $r) {
    //     return CustomerRegister::getNextNumber($r->all());
    // }

}