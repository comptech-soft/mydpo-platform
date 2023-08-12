<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\Centralizatoare\Access;

class CustomersCentralizatoareAccessController extends Controller {
    
    public function getRecords(Request $r) {
        return Access::getRecords($r->all());
    }

}