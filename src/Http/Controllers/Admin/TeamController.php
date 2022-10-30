<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\User;

class TeamController extends Controller {
    
    public function index(Request $r) {

        User::syncWithUserCustomer();

        return Response::View(
            '~templates.index', 
            asset('apps/team/index.js')
        );
        
    }

}