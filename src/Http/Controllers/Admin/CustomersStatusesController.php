<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\Statuses\Status;

class CustomersStatusesController extends Controller
{
    
    public function getItems(Request $r) {
        return Status::getItems($r->all());
    }

    public function getRecords(Request $r) {
        return Status::getRecords($r->all());
    }

}