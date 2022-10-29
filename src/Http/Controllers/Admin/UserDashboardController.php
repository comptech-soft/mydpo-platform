<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class UserDashboardController extends Controller {
    
    public function index($source, $user_id, Request $r) {


        return Response::View(
            '~templates.index', 
            asset('apps/user-dashboard/index.js'),
            [], 
            [
                'user_id' => $user_id,
                'source' => $source,
            ]
        );
    }

    
    
}