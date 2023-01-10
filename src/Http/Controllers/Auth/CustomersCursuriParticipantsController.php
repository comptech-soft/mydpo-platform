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

    public function importParticipants(Request $r) {
        return CustomerCursParticipant::importParticipants($r->all());
    }
    

}