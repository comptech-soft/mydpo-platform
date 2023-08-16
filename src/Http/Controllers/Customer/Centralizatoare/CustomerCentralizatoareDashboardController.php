<?php

namespace MyDpo\Http\Controllers\Customer\Centralizatoare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Livrabile\TipCentralizator;
use MyDpo\Models\Customer\Customer\Centralizatoare\Asociere;

class CustomerCentralizatoareDashboardController extends Controller {
    
    public function index($page, $customer_id, Request $r) {


        if(! ($customer =  Customer::find($customer_id)) )
        {
            return redirect('admin/clienti');
        }

        if(! in_array($page, ['centralizatoare', 'gap']))
        {
            return redirect('/');
        }

        $items = TipCentralizator::GetDashboardItems($page, $customer_id);

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/centralizatoare-dashboard/index.js'],
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

    // public function getCustomerAsociere(Request $r) {
    //     return TipCentralizator::getCustomerAsociere($r->all());
    // }

    // public function saveCustomerAsociere(Request $r) {
    //     return TipCentralizator::saveCustomerAsociere($r->all());
    // }





    // public function saveSettings(Request $r) {
    //     return CustomerCentralizator::saveSettings($r->all());
    // }

    // public function setAccess(Request $r) {
    //     return CustomerCentralizator::setAccess($r->all());
    // }

    // public function doImport(Request $r) {
    //     return CustomerCentralizator::doImport($r->all());
    // }


    
}