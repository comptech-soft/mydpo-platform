<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerFolder;

class CustomersInfograficeController extends Controller {
    
    public function index($customer_id) {

        $folder = CustomerFolder::where('customer_id', $customer_id)
            ->where('name', 'Infografice')
            ->where('platform', 'admin')
            ->where('type', 'infografice')
            ->whereNull('parent_id')
            ->where('deleted', 0)
            ->first();

        if(! $folder)
        {
            $folder = new CustomerFolder([
                'customer_id' => $customer_id,
                'name' => 'Infografice',
                'platform' => 'admin',
                'type' => 'infografice',
                'parent_id' => NULL,
                'deleted' => 0,
            ]);
            $folder->save();
        }

        return Response::View(
            '~templates.index', 
            asset('apps/customer-infografice/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }

}