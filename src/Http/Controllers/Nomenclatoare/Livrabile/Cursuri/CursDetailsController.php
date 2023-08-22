<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Cursuri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Curs;

class CursDetailsController extends Controller {
    
    public function index($curs_id, Request $r) {

        if(! ($curs =  Curs::find($curs_id)) )
        {
            return redirect('cursuri');
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/cursuri/curs-details/index.js'],
            payload: [
                'type' => 'curs',
                'curs_id' => $curs_id,
                'curs' => $curs,
            ],
        );

        // return Response::View(
        //     '~templates.index', 
        //     asset('apps/acces-curs/index.js'),
        //     [], 
        //     [
        //         'curs_id' => $curs_id
        //     ]
        // );
    }

    

}