<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Customer\CustomerAccount;

class UtilizatoriPlatformaController extends Controller {
    
    public function index(Request $r) {

        CustomerAccount::SyncRecords();

        return Response::View(
            '~templates.index', 
            asset('apps/utilizatori-platforma/index.js')
        );
    }

};