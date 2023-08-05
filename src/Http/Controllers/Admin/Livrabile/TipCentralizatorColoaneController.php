<?php

namespace MyDpo\Http\Controllers\Admin\Livrabile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Livrabile\TipCentralizator;

class TipCentralizatorColoaneController extends Controller {
    
    public function index($centralizator_id, Request $r) {

        dd($centralizator_id);
        
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/tip-centralizator-coloane/index.js']
        );        
    }

    // public function getRecords(Request $r) {
    //     return TipCentralizator::getRecords($r->all());
    // }

    // public function doAction($action, Request $r) {
    //     return TipCentralizator::doAction($action, $r->all());
    // }

    // public function getCustomerAsociere(Request $r) {
    //     return TipCentralizator::getCustomerAsociere($r->all());
    // }

    // public function saveCustomerAsociere(Request $r) {
    //     return TipCentralizator::saveCustomerAsociere($r->all());
    // }
    
}