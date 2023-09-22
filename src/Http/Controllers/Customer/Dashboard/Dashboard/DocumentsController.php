<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\Documents\Folder;
use MyDpo\Models\Customer\Documents\CustomerFolder;

class DocumentsController extends Controller {
    
    public function index($customer_id, Request $r) {

        Folder::CreateDefaultFolders($customer_id);

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/documents/index.js'],
            payload: [
                'type' => 'documente',
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }

    public function getRecords(Request $r) {
        return CustomerFolder::getRecords($r->all());
    }

    public function doAction(Request $r) {
        return CustomerFolder::doAction($r->all());
    }

    // public function downloadFile($customer_id, $file_id, Request $r) {
    //     return CustomerFile::downloadFile($customer_id, $file_id);
    // }
    

}