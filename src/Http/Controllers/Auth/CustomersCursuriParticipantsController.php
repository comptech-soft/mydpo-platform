<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerCursParticipant;

class CustomersCursuriParticipantsController extends Controller {
    
    public function getItems(Request $r) {
        return CustomerCursParticipant::getItems($r->all());
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