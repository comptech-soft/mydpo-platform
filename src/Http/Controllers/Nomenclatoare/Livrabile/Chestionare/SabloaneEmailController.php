<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Chestionare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Nomenclatoare\Livrabile\Chestionare\SablonEmail;

class SabloaneEmailController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/sabloane-email/index.js'],
        );        
    }

    public function getRecords(Request $r) {
        return SablonEmail::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return SablonEmail::doAction($action, $r->all());
    }
 
}