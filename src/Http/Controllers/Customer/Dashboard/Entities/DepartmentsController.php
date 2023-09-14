<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Entities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\Departments\Department;

class DepartmentsController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/departamente/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
                'customer_user' => \Auth::user(),
            ],
        );        
    }    

    public function getRecords(Request $r) {
        return Department::getRecords($r->all());
    }
    
    public function doAction($action, Request $r) {
        return Department::doAction($action, $r->all());
    }

}