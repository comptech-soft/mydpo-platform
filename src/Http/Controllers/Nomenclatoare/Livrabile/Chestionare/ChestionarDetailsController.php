<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Chestionare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\Chestionare\Chestionar;

class ChestionarDetailsController extends Controller {
    
    public function index($id, Request $r) {

        $chestionar = Chestionar::find($id);

        if(! $chestionar )
        {
            return redirect('chestionare');
        }

        $chestionar->CalculateUserScores();

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/chestionar-details/index.js'],
            payload: [
                'chestionar' => $chestionar,
            ],
        );        
    }


}