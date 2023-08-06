<?php

namespace MyDpo\Http\Controllers\Admin\Customer\Centralizatoare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\Livrabile\TipCentralizator;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizator;

class CustomerCentralizatorController extends Controller {
    
    public function index($customer_id, $centralizator_id, Request $r) {
        
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/centralizator/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'centralizator_id' => $centralizator_id,
                'customer' => Customer::find($customer_id),
                'centralizator' => TipCentralizator::find($centralizator_id),
            ],
        );        
    }

    public function getRecords(Request $r) {
        return CustomerCentralizator::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerCentralizator::doAction($action, $r->all());
    }

    public function getNextNumber(Request $r) {
        return CustomerCentralizator::getNextNumber($r->all());
    }

    // public function doExport(Request $r) {
    //     return CustomerCentralizator::doExport($r->all());
    // }

}