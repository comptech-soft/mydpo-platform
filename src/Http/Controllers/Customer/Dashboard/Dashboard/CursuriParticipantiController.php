<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Customer\ELearning\CustomerCursParticipant;

class CursuriParticipantiController extends Controller {
    
    public function getRecords(Request $r) {
        return CustomerCursParticipant::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return CustomerCursParticipant::doAction($action, $r->all());
    }
    
}