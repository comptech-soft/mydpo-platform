<?php

namespace MyDpo\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Database;

class DatabaseController extends Controller
{

    public function updateField(Request $r) {
        dd($r->all());
    }

   

    

}