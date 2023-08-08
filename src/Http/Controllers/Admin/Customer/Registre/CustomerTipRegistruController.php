<?php

namespace MyDpo\Http\Controllers\Admin\Customer\Registre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\Livrabile\TipRegistru;
// use MyDpo\Models\Customer\CustomerRegister;

class CustomerTipRegistruController extends Controller {
    
    public function index($customer_id, $register_id, Request $r) {

        if( ! ($customer = Customer::find($customer_id)) )
        {
            return redirect('/');
        }

        if( ! ($tip = TipRegistru::find($register_id)) )
        {
            return redirect('/');
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/customers-registre-list/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'tip_id' => $register_id,
                'customer' => $customer,
                'tip' => $tip,
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