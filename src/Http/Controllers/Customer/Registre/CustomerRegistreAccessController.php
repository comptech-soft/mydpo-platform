<?php

namespace MyDpo\Http\Controllers\Customer\Registre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\Customer\Registre\Access;

class CustomerRegistreAccessController extends Controller {
    
    public function getRecords(Request $r) {
        return Access::getRecords($r->all());
    }

}