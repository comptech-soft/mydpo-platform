<?php

namespace MyDpo\Http\Controllers\Admin\Customer\Centralizatoare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\Centralizatoare\Access;

class CustomerCentralizatoareAccessController extends Controller {
    
    public function getRecords(Request $r) {
        return Access::getRecords($r->all());
    }

}