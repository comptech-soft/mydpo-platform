<?php

namespace MyDpo\Http\Controllers\Admin\Customer\Centralizatoare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\Livrabile\TipCentralizator;
use MyDpo\Models\Customer\Centralizatoare\Centralizator;

class CustomerTipCentralizatorController extends Controller {
    
    public function index($customer_id, $centralizator_id, Request $r) {
        
        if( ! ($customer = Customer::find($customer_id)) )
        {
            return redirect('/');
        }

        if( ! ($tip = TipCentralizator::find($centralizator_id)) )
        {
            return redirect('/');
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/customers-centralizatoare-list/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'tip_id' => $centralizator_id,
                'customer' => $customer,
                'tip' => $tip,
                'model' => 'Customercentralizatoare',
            ],
        );    

      
    }

    public function getRecords(Request $r) {
        return Centralizator::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Centralizator::doAction($action, $r->all());
    }

    // public function getNextNumber(Request $r) {
    //     return CustomerCentralizator::getNextNumber($r->all());
    // }

    // public function doExport(Request $r) {
    //     return CustomerCentralizator::doExport($r->all());
    // }

}