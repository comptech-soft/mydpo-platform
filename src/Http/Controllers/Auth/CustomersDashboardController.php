<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class CustomersDashboardController extends Controller {
    
    public function index($customer_id,Request $r) {

        dd($customer_id);

        return Response::View(
            '~templates.index', 
            asset('apps/centralizatoare/index.js')
        );
    }


}