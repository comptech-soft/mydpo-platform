<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\ServiceType;

class ServiciiTypesController extends Controller {
    
    public function getItems(Request $r) {
        return ServiceType::getItems($r->all());
    }
}