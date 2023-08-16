<?php

namespace MyDpo\Http\Controllers\Admin\Customer\Centralizatorable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;

use MyDpo\Models\Customer;

use MyDpo\Models\Livrabile\TipCentralizator;
use MyDpo\Models\Customer\Centralizatoare\Centralizator;
use MyDpo\Models\Customer\Centralizatoare\Row as CentralizatorRow;

use MyDpo\Models\Livrabile\TipRegistru;
use MyDpo\Models\Customer\Registre\Registru;
use MyDpo\Models\Customer\Registre\Row as RegistruRow;

class CentralizatorableRowsController extends Controller {
    
    protected $myclasses = [
        'centralizatoare' => [
            'tip' => TipCentralizator::class,
            'document' => Centralizator::class,
            'row' => CentralizatorRow::class,
        ],

        'registre' => [
            'tip' => TipCentralizator::class,
            'document' => Registru::class,
            'row' => RegistruRow::class,
        ],

    ];

    public function index($model, $page, $customer_id, $tip_id, $document_id, Request $r) {
        
        if( ! ($customer = Customer::find($customer_id)) )
        {
            return redirect('admin/clienti');
        }

        if(! in_array($model, ['centralizatoare', 'registre']))
        {
            return redirect('customer-dashboard/' . $customer_id);
        }

        if( ($model == 'centralizatoare') && ! in_array($page, ['centralizatoare', 'gap']))
        {
            return redirect('customer-dashboard/' . $customer_id);
        }

        if( ($model == 'registre') && ! in_array($page, ['registre', 'gap']))
        {
            return redirect('customer-dashboard/' . $customer_id);
        }
        
        dd(__FILE__);

        if( ! ($tip = ($model == 'centralizatoare' ? TipCentralizator::find($tip_id) : TipRegistru::find($tip_id) ) ) )
        {
            return redirect($model . '-dashboard/' . $page . '/' . $customer_id);
        }

        if( ! ($document = ($model == 'centralizatoare' ? Centralizator::find($document_id) : Registru::find($document_id) )) )
        {
            return redirect($model . '-list/' . $page . '/' . $customer_id . '/' . $tip_id);
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/centralizatorable-rows/index.js'],
            payload: [
                'page' => $page,
                'model' => $model,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'tip_id' => $tip_id,
                'tip' => $tip,
                'document_id' => $document_id,
                'document' => $document
            ],
        );
    }

    public function getCentralizatorRows(Request $r) {
        return CentralizatorRow::getRecords($r->all());
    }

    public function getRegistruRows(Request $r) {
        return RegistruRow::getRecords($r->all());
    }

    public function doAction($action, Request $r) {

        if($r->model == 'centralizatoare')
        {
            return CentralizatorRow::doAction($action, $r->all());
        }

        if($r->model == 'registre')
        {
            return RegistruRow::doAction($action, $r->all());
        }

    }

}