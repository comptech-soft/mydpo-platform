<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class TeamController extends Controller {
    
    public function index(Request $r) {

        $asset = (config('app.platform') == 'admin') ? 'team' : 'my-team';

        return Response::View(
            '~templates.index', 
            asset('apps/' . $asset . '/index.js')
        );
    }

}