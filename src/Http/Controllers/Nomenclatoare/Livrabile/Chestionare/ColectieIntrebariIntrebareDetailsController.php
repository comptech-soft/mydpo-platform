<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Chestionare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Nomenclatoare\Livrabile\Chestionare\Question;

class ColectieIntrebariIntrebareDetailsController extends Controller {
    
    public function index($id, Request $r) {

        $question = Question::find($id);

        if(! $question )
        {
            return redirect('colectie-intrebari');
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/colectie-intrebari-intrebare-details/index.js'],
            payload: [
                'question' => $question,
            ],
        );        
    }


}