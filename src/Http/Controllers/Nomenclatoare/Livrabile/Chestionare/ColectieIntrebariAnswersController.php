<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Chestionare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Nomenclatoare\Livrabile\Chestionare\QuestionAnswer;

class ColectieIntrebariAnswersController extends Controller {
    
    public function getRecords(Request $r) {
        return QuestionAnswer::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return QuestionAnswer::doAction($action, $r->all());
    }
 
}