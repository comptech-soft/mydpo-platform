<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Contracts\OrderService;

class ServiciiComandateController extends Controller {


    public function index(Request $r) {

        OrderService::syncWithOrders();

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/servicii-comandate/index.js'],
        );        
    }

}