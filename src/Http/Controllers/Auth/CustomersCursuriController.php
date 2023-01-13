<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerCurs;

class CustomersCursuriController extends Controller {
    
    public function index($customer_id,Request $r) {

        CustomerCurs::syncUsersCounts($customer_id);

        return Response::View(
            '~templates.index', 
            asset('apps/customer-cursuri/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

    public function indexMyCursuri($customer_id,Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/cursurile-mele/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

    public function downloadFile($customer_id, $file_id, Request $r) {
        return CustomerCurs::downloadFile($customer_id, $file_id);
    }

    public function getItems(Request $r) {
        return CustomerCurs::getItems($r->all());
    }

    public function getSummary(Request $r) {
        return CustomerCurs::getSummary($r->all());
    }

    public function desasociereUtilizatori(Request $r) {
        return CustomerCurs::desasociereUtilizatori($r->all());
    }

    
    
}