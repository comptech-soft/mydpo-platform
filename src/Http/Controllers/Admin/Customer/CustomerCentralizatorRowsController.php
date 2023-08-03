<?php

namespace MyDpo\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\Livrabile\Centralizator;
use MyDpo\Models\Customer\CustomerCentralizator;
use MyDpo\Models\Customer\CustomerCentralizatorRow;

class CustomerCentralizatorRowsController extends Controller {
    
    public function index($customer_centralizator_id, $customer_id, $centralizator_id, Request $r) {

        $customer_centralizator = CustomerCentralizator::find($customer_centralizator_id);

        if(! $customer_centralizator)
        {
            return redirect()->back();
        }

        if(! $customer_centralizator->current_columns)
        {
            $customer_centralizator->SetCurrentColumns();
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/centralizator-rows/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'centralizator_id' => $centralizator_id,
                'customer_centralizator_id' => $customer_centralizator_id,

                'customer' => Customer::find($customer_id),
                'centralizator' => Centralizator::find($centralizator_id),
                'customer_centralizator' => $customer_centralizator,
            ],
        );

    }

    public function getRecords(Request $r) {
        return CustomerCentralizatorRow::getRecords($r->all());
    }

    public function insertRow(Request $r) {
        return CustomerCentralizatorRow::insertRow($r->all());
    }

    public function updateRow(Request $r) {
        return CustomerCentralizatorRow::updateRow($r->all());
    }

    public function deleteRow(Request $r) {
        return CustomerCentralizatorRow::deleteRow($r->all());
    }

    public function deleteRows(Request $r) {
        return CustomerCentralizatorRow::deleteRows($r->all());
    }
    
    public function setRowsStatus(Request $r) {
        return CustomerCentralizatorRow::setRowsStatus($r->all());
    }
    
    // public function setRowsVisibility(Request $r) {
    //     return CustomerCentralizatorRow::setRowsVisibility($r->all());
    // }

    
    
}