<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\Customer\Centralizatoare\Access;

class CustomerCentralizatoareAccessController extends Controller {
    
    public function getRecords(Request $r) {
        return Access::getRecords($r->all());
    }

}