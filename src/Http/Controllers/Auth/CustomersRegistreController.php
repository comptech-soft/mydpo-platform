<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerRegister;

class CustomersRegistreController extends Controller {
    
    public function index($customer_id, Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/customer-registre/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

    public function getItems(Request $r) {
        return CustomerRegister::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerRegister::doAction($action, $r->all());
    }

    public function getNextNumber(Request $r) {
        return CustomerRegister::getNextNumber($r->all());
    }

    public function registerDownloadPreview($id) {

        return view('exports.customer-register.export', [

        ]);

        // $centralizator = Centralizator::where('id', $id)->with(['columns'])->first();
        // return view('exports.centralizator.xls-export', [
        //    'records' => $centralizator->columns,
        // ]);
        
    }

    public function registerDownload(Request $r) {
        return CustomerRegister::registerDownload($r->all());
    }

}