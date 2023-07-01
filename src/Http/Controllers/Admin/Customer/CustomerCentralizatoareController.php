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
            ],
        );        
    }

    // public function index($customer_id, Request $r) {

    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/customer-centralizatoare/index.js'),
    //         [], 
    //         [
    //             'customer_id' => $customer_id,
    //             'gap' => 0,
    //             'centralizatoare' => 1,
    //         ]
    //     );
    // }

    // public function getRecords(Request $r) {
    //     return CustomerCentralizator::getRecords($r->all());
    // }

    // public function getNextNumber(Request $r) {
    //     return CustomerCentralizator::getNextNumber($r->all());
    // }

    // public function doExport(Request $r) {
    //     return CustomerCentralizator::doExport($r->all());
    // }

    // public function saveSettings(Request $r) {
    //     return CustomerCentralizator::saveSettings($r->all());
    // }

    // public function setAccess(Request $r) {
    //     return CustomerCentralizator::setAccess($r->all());
    // }

    // public function doImport(Request $r) {
    //     return CustomerCentralizator::doImport($r->all());
    // }

    // public function doAction($action, Request $r) {
    //     return CustomerCentralizator::doAction($action, $r->all());
    // }
    
}