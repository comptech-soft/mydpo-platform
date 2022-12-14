<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use MyDpo\Helpers\Response;

class UserDashboardController extends Controller {
    
    public function index($source, $user_id, $customer_id = NULL) {
        return Response::View(
            '~templates.index', 
            asset('apps/user-dashboard/index.js'),
            [], 
            [
                'user_id' => $user_id,
                'customer_id' => $customer_id,
                'source' => $source,
            ]
        );
    }
    
}