<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerCursFile;

class CustomersCursuriFilesController extends Controller {
    
    public function getItems(Request $r) {
        return CustomerCursFile::getItems($r->all());
    }

    // public function getCounter(Request $r) {
    //     return CustomerCursUser::getCounter($r->all());
    // }
    
    // public function changeStatus(Request $r) {
    //     return CustomerCursUser::changeStatus($r->all());
    // }

    public function attachFiles(Request $r) {
        dd($r->all());
        // return CustomerCursUser::assignCursuri($r->all());
    }

}