<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Chestionare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Nomenclatoare\Livrabile\Chestionare\Question;

class ColectieIntrebariIntrebareDetailsController extends Controller {
    
    public function index($id, Request $r) {

        dd($id, $r->all());
        
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/colectie-intrebari/index.js'],
        );        
    }


}