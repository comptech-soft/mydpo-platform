<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Customer;

class CustomersController extends Controller
{
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/customers/index.js')
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