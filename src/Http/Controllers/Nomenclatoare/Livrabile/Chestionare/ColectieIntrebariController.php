<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Chestionare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Nomenclatoare\Livrabile\Chestionare\Question;

class ColectieIntrebariController extends Controller {
    
    public function index(Request $r) {

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/colectie-intrebari/index.js'],
        );        
    }

    public function getRecords(Request $r) {
        return Question::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Question::doAction($action, $r->all());
    }
 
}