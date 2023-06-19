<?php

namespace MyDpo\Http\Controllers\Admin\Livrabile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Planconformare;

class PlanconformareController extends Controller 
{
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/planuri-conformare/index.js']
        );        
    }

    // public function getRecords(Request $r) {
    //     return Planconformare::getRecords($r->all());
    // }

    // public function doAction($action, Request $r) {
    //     return Planconformare::doAction($action, $r->all());
    // }

    // public function reorder(Request $r) {
    //     return Planconformare::reorder($r->all());
    // }

    // public function getCustomerAsociere(Request $r) {
    //     return Centralizator::getCustomerAsociere($r->all());
    // }

    // public function saveCustomerAsociere(Request $r) {
    //     return Centralizator::saveCustomerAsociere($r->all());
    // }
    


}