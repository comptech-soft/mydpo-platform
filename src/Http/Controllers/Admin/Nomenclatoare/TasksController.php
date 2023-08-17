<?php

namespace MyDpo\Http\Controllers\Admin\Nomenclatoare;

use App\Http\Controllers\Controller;
use MyDpo\Core\Http\Response\Index;

class TasksController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/tasks/index.js']
        );        
    }

    // public function index(Request $r) {
    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/taskuri/index.js')
    //     );
    // }

}