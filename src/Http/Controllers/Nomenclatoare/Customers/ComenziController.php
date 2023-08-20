<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;

class ComenziController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/comenzi/index.js'],
        );        
    }

}