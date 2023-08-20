<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Cursuri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Curs;

class CursuriTrimitereController extends Controller {
    
    public function index(Request $r) {

        // Curs::calculateInfos();

        // return Response::View(
        //     '~templates.index', 
        //     asset('apps/elearning/index.js')
        // );

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/cursuri-trimitere/index.js'],
            
        );
    }

    


}