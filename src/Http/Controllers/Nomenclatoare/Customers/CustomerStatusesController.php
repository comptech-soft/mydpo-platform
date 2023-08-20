<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\Statuses\Status;

class CustomerStatusesController extends Controller {
    
    public function getItems(Request $r) {
        return Status::getItems($r->all());
    }

    public function getRecords(Request $r) {
        return Status::getRecords($r->all());
    }

}