<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Livrabile\Registre\TipRegistru;
use MyDpo\Models\Customer\Customer\Registre\Asociere;

class RegistreDashboardController extends Controller {
    
    public function index($page, $customer_id, Request $r) {
        
        if(! ($customer =  Customer::find($customer_id)) )
        {
            return redirect('admin/clienti');
        }

        if(! in_array($page, ['registre', 'gap']))
        {
            return redirect('/');
        }

        $items = TipRegistru::GetDashboardItems($page, $customer_id);

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/registre-dashboard/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => $customer,
                'page' => $page,
                'tipuri' => $items,
            ],
        );     

    }

    public function doAction($action, Request $r) {
        return Asociere::doAction($action, $r->all());
    }


    // public function getItems(Request $r) {
    //     return CustomerRegister::getItems($r->all());
    // }


    // public function getNextNumber(Request $r) {
    //     return CustomerRegister::getNextNumber($r->all());
    // }

    // public function registerCopy(Request $r) {
    //     return CustomerRegister::registerCopy($r->all());
    // }

    // public function registerDownloadPreview($id) {

    //     $registru = CustomerRegister::where('id', $id)->first();

    //     return view('exports.customer-register.export', [
    //         'columns' => $registru->columns,
    //         'records' => $registru->records,
    //         'children' => $registru->children_columns,
    //     ]);
    // }

    // public function registerDownload(Request $r) {
    //     return CustomerRegister::registerDownload($r->all());
    // }

    // public function registerUpload(Request $r) {
    //     return CustomerRegister::registerUpload($r->all());
    // }

    // public function registerSaveAccess(Request $r) {
    //     return CustomerRegister::registerSaveAccess($r->all());
    // }

    

}