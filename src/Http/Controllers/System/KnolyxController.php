<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use MyDpo\Helpers\Response;

class KnolyxController extends Controller {

    public function webhookProcess(Request $r) {
        return 'OK';
    }

}