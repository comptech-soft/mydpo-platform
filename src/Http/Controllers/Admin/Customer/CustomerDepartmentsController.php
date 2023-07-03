<?php

namespace MyDpo\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;

class CustomerDepartmentsController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/departamente/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }    

    public function getRecords(Request $r) {
        return CustomerDepartment::getRecords($r->all());
    }
    
    public function doAction($action, Request $r) {
        return CustomerDepartment::doAction($action, $r->all());
    }

}