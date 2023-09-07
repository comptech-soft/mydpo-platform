<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\ELearning\CustomerCursUser;

class CursuriUsersController extends Controller {
    
    public function getRecords(Request $r) {
        return CustomerCursUser::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerCursUser::doAction($action, $r->all());
    }

    // public function getCounter(Request $r) {
    //     return CustomerCursUser::getCounter($r->all());
    // }
    
    // public function changeStatus(Request $r) {
    //     return CustomerCursUser::changeStatus($r->all());
    // }

    // public function assignCursuri(Request $r) {
    //     return CustomerCursUser::assignCursuri($r->all());
    // }

    

}