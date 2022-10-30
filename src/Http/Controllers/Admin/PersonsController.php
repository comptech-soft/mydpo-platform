<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerAccount;

class PersonsController extends Controller {
    
    public function index() {
        CustomerAccount::SyncRecords();

        return Response::View(
            '~templates.index', 
            asset('apps/persons/index.js')
        );
    }

}