<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Livrabile\Cursuri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Curs;

class CursuriController extends Controller {
    
    public function index(Request $r) {

        Curs::getKnolyxCourses();
        Curs::CalculateInfos();
        
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/livrabile/cursuri/cursuri/index.js'],
            payload: [
                'type' => 'curs',
            ],
        );
    }

    public function getRecords(Request $r) {
        return Curs::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Curs::doAction($action, $r->all());
    }

    // public function getKnolyxCourses(Request $r) {
    //     return 
    // }

    // public function openKnolyxCourse(Request $r) {
    //     return Curs::openKnolyxCourse($r->all());
    // }
}