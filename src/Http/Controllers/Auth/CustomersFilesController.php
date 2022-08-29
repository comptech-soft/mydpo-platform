<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerFile;

class CustomersFilesController extends Controller
{
    public function doAction($action, Request $r) {
        return CustomerFile::doAction($action, $r->all());
    }

}