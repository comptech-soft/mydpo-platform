<?php

namespace MyDpo\Http\Controllers\Admin\Customer\Centralizatorable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Customer;
// use MyDpo\Models\Livrabile\TipCentralizator;
// use MyDpo\Models\Customer\Centralizatoare\CentralizatorAsociat;

class CustomerCentralizatoareDashboardController extends Controller {


    public function index($model, $customer_id, $tip_id, $document_id, Request $r) {
        
        dd($model, $customer_id, $tip_id, $document_id);
        
        // if( ! ($customer = Customer::find($customer_id)) )
        // {
        //     return redirect('/');
        // }

        // if( ! ($tip = TipCentralizator::find($centralizator_id)) )
        // {
        //     return redirect('/');
        // }

        // return Index::View(
        //     styles: ['css/app.css'],
        //     scripts: ['apps/customer/customers-centralizatoare-list/index.js'],
        //     payload: [
        //         'customer_id' => $customer_id,
        //         'tip_id' => $centralizator_id,
        //         'customer' => $customer,
        //         'tip' => $tip,
        //         'model' => 'Customercentralizatoare',
        //     ],
        // );    

      
    }

}