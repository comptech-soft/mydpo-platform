<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\SharematerialDetail;

class CustomersShareMaterialeDetailsController extends Controller
{
    
    public function getItems(Request $r) {
        return SharematerialDetail::getItems($r->all());
    }

}