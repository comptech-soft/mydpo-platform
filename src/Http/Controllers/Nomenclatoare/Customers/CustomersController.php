<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;

class CustomersController extends Controller {
    
    public function index(Request $r) {

        Customer::beforeShowIndex();

        dd(__METHOD__);

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/customers/customers/index.js'],
        );
        
    }

    public function getItems(Request $r) {
        return Customer::getItems($r->all());
    }

    public function getRecords(Request $r) {
        return Customer::getRecords($r->all());
    }

    public function getItemsWithPersons(Request $r) {
        return Customer::getItemsWithPersons($r->all());
    }

    public function getCustomersByIds(Request $r) {
        return Customer::getCustomersByIds($r->all());
    }
    
    public function doAction($action, Request $r) {
        return Customer::doAction($action, $r->all());
    }

}