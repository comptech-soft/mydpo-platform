<?php

namespace MyDpo\Http\Controllers\Admin\Customer\Centralizator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\Livrabile\TipCentralizator;

class CustomerCentralizatoareDashboardController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/centralizatoare-dashboard/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
                'gap' => 0,
                'centralizatoare' => 1,
            ],
        );        
    }

    public function getCustomerAsociere(Request $r) {
        return TipCentralizator::getCustomerAsociere($r->all());
    }

    public function saveCustomerAsociere(Request $r) {
        return TipCentralizator::saveCustomerAsociere($r->all());
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