<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerCentralizator;

class CustomersCentralizatoareController extends Controller {
    
    public function index($customer_id, Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/customer-centralizatoare/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

    public function getRecords(Request $r) {
        return CustomerCentralizator::getRecords($r->all());
    }

    public function getNextNumber(Request $r) {
        return CustomerCentralizator::getNextNumber($r->all());
    }

    public function doExport(Request $r) {
        return CustomerCentralizator::doExport($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerCentralizator::doAction($action, $r->all());
    }
    
}