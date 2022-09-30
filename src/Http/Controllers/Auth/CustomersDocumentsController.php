<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Customer;
use MyDpo\Models\CustomerFile;

class CustomersDocumentsController extends Controller {
    
    public function index($customer_id, Request $r) {

        $customer = Customer::find($customer_id);
        $customer->createDefaultFolders();

        return Response::View(
            '~templates.index', 
            asset('apps/customer-documents/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

    public function downloadFile($customer_id, $file_id, Request $r) {
        return CustomerFile::downloadFile($customer_id, $file_id);
    }
    

}