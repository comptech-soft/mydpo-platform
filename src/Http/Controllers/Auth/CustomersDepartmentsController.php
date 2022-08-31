<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\CustomerDepartment;

class CustomersDepartmentsController extends Controller
{
    
    public function getItems(Request $r) {
        return CustomerDepartment::getItems($r->all());
    }
    
    public function doAction($action, Request $r) {
        return CustomerDepartment::doAction($action, $r->all());
    }

}