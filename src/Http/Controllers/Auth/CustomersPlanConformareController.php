<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerPlanconformare;

class CustomersPlanConformareController extends Controller {
    
    public function index($customer_id, Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/customer-plan-conformare/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
           
    }

    public function getRecords(Request $r) {
        return CustomerPlanconformare::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerPlanconformare::doAction($action, $r->all());
    }

    public function getNextNumber(Request $r) {
        return CustomerPlanconformare::getNextNumber($r->all());
    }

}