<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Contracts\Contract;

class ContracteController extends Controller {
    
    public function index(Request $r) {
        
        Contract::CalculateContractExpirat();

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/customers/contracte/index.js'],
        );        
    }

}