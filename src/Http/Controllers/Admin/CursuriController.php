<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Curs;

class CursuriController extends Controller {
    
    public function index(Request $r) {

        Curs::calculateInfos();

        return Response::View(
            '~templates.index', 
            asset('apps/elearning/index.js')
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