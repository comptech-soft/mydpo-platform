<?php

namespace MyDpo\Http\Controllers\Customer\Registre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\Livrabile\TipRegistru;
use MyDpo\Models\Customer\Registre\Registru;

class CustomerTipRegistruController extends Controller {
    
    public function index($page, $customer_id, $tip_id, Request $r) {

        if( ! ($customer = Customer::find($customer_id)) )
        {
            return redirect('admin/clienti');
        }

        if(! in_array($page, ['registre', 'gap']))
        {
            return redirect('customer-dashboard/' . $customer_id);
        }

        if( ! ($tip = TipRegistru::find($tip_id)) )
        {
            return redirect('registre-dashboard/' . $page . '/' . $customer_id);
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/customers-registre-list/index.js'],
            payload: [
                'page' => $page,
                'customer_id' => $customer_id,
                'tip_id' => $tip_id,
                'customer' => $customer,
                'tip' => $tip,
                'model' => 'Customerregistre',
            ],
        );        
    }

    public function getRecords(Request $r) {
        return Registru::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Registru::doAction($action, $r->all());
    }

}