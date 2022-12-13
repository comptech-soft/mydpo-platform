<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerCursUser;

class CustomersCursuriUsersController extends Controller {
    
    public function getItems(Request $r) {
        return CustomerCursUser::getItems($r->all());
    }

    public function getSummary(Request $r) {
        return CustomerCursUser::getSummary($r->all());
    }
    
}