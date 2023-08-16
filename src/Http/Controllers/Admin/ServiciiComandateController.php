<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use MyDpo\Helpers\Response;
use MyDpo\Models\Customer\CustomerService;

class ServiciiComandateController extends Controller {

    public function index() {

        CustomerService::syncWithOrders();

        return Response::View(
            '~templates.index', 
            asset('apps/servicii-comandate/index.js')
        );
    }

}