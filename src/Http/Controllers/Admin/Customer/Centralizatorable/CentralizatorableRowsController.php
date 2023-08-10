<?php

namespace MyDpo\Http\Controllers\Admin\Customer\Centralizatorable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\Livrabile\TipCentralizator;
use MyDpo\Models\Livrabile\TipRegistru;

// use MyDpo\Models\Customer\Centralizatoare\CentralizatorAsociat;

class CentralizatorableRowsController extends Controller {


    public function index($model, $customer_id, $tip_id, $document_id, Request $r) {
        
        if(! in_array($model, ['centralizatoare', 'registre']))
        {
            return redirect('/');
        }

        if( ! ($customer = Customer::find($customer_id)) )
        {
            return redirect('/');
        }

        $tip = ($model == 'centralizatoare' ? TipCentralizator::find($tip_id) : TipRegistru::find($tip_id) );
        if( ! $tip )
        {
            return redirect('/');
        }

        // dd($model, $customer_id, $tip_id, $document_id);

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/centralizatorable-rows/index.js'],
            payload: [
                'model' => $model,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'tip_id' => $tip_id,
                'tip' => $tip,
            ],
        );    

      
    }

}