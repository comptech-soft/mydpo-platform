<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\Chestionare\CustomerChestionarUser;

class ChestionareUsersController extends Controller {
    
    public function getRecords(Request $r) {
        return CustomerChestionarUser::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerChestionarUser::doAction($action, $r->all());
    }
    
}