<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerCentralizatorRow;

class CustomersCentralizatoareRowsController extends Controller {
    
    public function getRecords(Request $r) {
        return CustomerCentralizatorRow::getRecords($r->all());
    }

    public function insertRow(Request $r) {
        return CustomerCentralizatorRow::insertRow($r->all());
    }

    public function setRowsStatus(Request $r) {
        return CustomerCentralizatorRow::setRowsStatus($r->all());
    }
    
    public function setRowsVisibility(Request $r) {
        return CustomerCentralizatorRow::setRowsVisibility($r->all());
    }
    
}