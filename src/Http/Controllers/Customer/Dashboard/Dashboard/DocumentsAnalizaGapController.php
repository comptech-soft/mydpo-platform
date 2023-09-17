<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;

class DocumentsAnalizaGapController extends Controller {
    
    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/documents-analizagap/index.js'],
            payload: [
                'type' => 'analizagap',
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }

    // public function index($customer_id, Request $r) {

    //     $customer = Customer::find($customer_id);
    //     $customer->createDefaultFolders();

    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/customer-documents/index.js'),
    //         [], 
    //         [
    //             'customer_id' => $customer_id,
    //         ]
    //     );
    // }

    // public function downloadFile($customer_id, $file_id, Request $r) {
    //     return CustomerFile::downloadFile($customer_id, $file_id);
    // }
    

}