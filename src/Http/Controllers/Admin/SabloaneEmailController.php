<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class SabloaneEmailController extends Controller
{
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/sabloane-email/index.js')
        );
    }

}