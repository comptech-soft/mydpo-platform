<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Centralizator;

class PlanconformareController extends Controller {
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/plan-conformare/index.js')
        );
    }

    // public function getRecords(Request $r) {
    //     return Centralizator::getRecords($r->all());
    // }

    // public function getCustomerAsociere(Request $r) {
    //     return Centralizator::getCustomerAsociere($r->all());
    // }

    // public function saveCustomerAsociere(Request $r) {
    //     return Centralizator::saveCustomerAsociere($r->all());
    // }
    
    // public function doAction($action, Request $r) {
    //     return Centralizator::doAction($action, $r->all());
    // }

}