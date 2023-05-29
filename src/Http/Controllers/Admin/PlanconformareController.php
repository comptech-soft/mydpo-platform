<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Planconformare;

class PlanconformareController extends Controller {
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/plan-conformare/index.js')
        );
    }

    public function getRecords(Request $r) {
        return Planconformare::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Planconformare::doAction($action, $r->all());
    }

    // public function getCustomerAsociere(Request $r) {
    //     return Centralizator::getCustomerAsociere($r->all());
    // }

    // public function saveCustomerAsociere(Request $r) {
    //     return Centralizator::saveCustomerAsociere($r->all());
    // }
    


}