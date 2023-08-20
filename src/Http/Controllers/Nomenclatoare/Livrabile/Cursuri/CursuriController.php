<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Cursuri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Curs;

class CursuriController extends Controller {
    
    public function index(Request $r) {

        // Curs::calculateInfos();

        // return Response::View(
        //     '~templates.index', 
        //     asset('apps/elearning/index.js')
        // );

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/cursuri/index.js'],
        );
    }

    

    public function getKnolyxCourses(Request $r) {
        return Curs::getKnolyxCourses($r->all());
    }

    public function openKnolyxCourse(Request $r) {
        return Curs::openKnolyxCourse($r->all());
    }

    public function getItems(Request $r) {
        return Curs::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return Curs::doAction($action, $r->all());
    }

}