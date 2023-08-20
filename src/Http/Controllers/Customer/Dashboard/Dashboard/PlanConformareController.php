<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\Planuriconformare\Planconformare;

class PlanConformareController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/plan-conformare/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }

    public function getRecords(Request $r) {
        return Planconformare::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Planconformare::doAction($action, $r->all());
    }

    // public function getNextNumber(Request $r) {
    //     return CustomerPlanconformare::getNextNumber($r->all());
    // }

    // public function saveRows(Request $r) {
    //     return CustomerPlanconformare::saveRows($r->all());
    // }

}